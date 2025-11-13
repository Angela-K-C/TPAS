<?php

namespace App\Http\Controllers;

use App\Models\TemporaryPass;
use App\Models\Student;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTemporaryPassRequest;
use App\Http\Requests\UpdateTemporaryPassRequest;
use App\Mail\WelcomeMail;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Encoder\QpMimeHeaderEncoder;

class TemporaryPassController extends Controller
{
    /**
     * Display a listing of the resource (READ).
     * Admins see all passes. Students/Guests only see their own.
     */
    public function index()
    {
        // Check Admin Guard (web)
        if (Auth::guard('web')->check()) {
            $passes = TemporaryPass::with('passable')->latest()->get();

        } else if (Auth::guard('university')->check()) {
            $user = Auth::guard('university')->user();

            $passes = TemporaryPass::where('passable_type', $user->getMorphClass()) 
                                ->where('passable_id', $user->id)
                                ->latest()
                                ->get();

        } else if(Auth::guard('guest')->check()) {
            $guest = Auth::guard('guest')->user();

            $passes = TemporaryPass::where('passable_type', $guest->getMorphClass()) 
                                ->where('passable_id', $guest->id)
                                ->latest()
                                ->get();
        } else {
            abort(403, 'Unauthorized');
        }

        return view('test.passes.index', compact('passes'));
    }

    /**
     * Apply for a new temporary pass (Student/Guest)
     */
    public function store(Request $request) 
    {
        if (!Auth::guard('university')->check() && !Auth::guard('guest')->check()) {
            abort(403, 'Unauthorized');
        }


        $request->validate([
            'reason' => 'required|string|max:255',
        ]);
        
        $pass = new TemporaryPass();
        $pass->reason = $request->input('reason');
        $pass->status = 'pending'; 
        
        if (Auth::guard('university')->check()) {
            $pass->passable()->associate(Auth::guard('university')->user());
        }

        if (Auth::guard('guest')->check()) {
            $pass->passable()->associate(Auth::guard('guest')->user());
        }

        $pass->save();

        return redirect()->route('passes.index')->with('success', 'Temporary pass application submitted!');
    }

    /**
     * Display the specified resource (READ one).
     */
    public function show(TemporaryPass $temporaryPass)
    {
        if (Auth::guard('web')->check()) {
            $temporaryPass->load('passable');

        } else if (Auth::guard('university')->check()) {
            $user = Auth::guard('university')->user();
            
            // User tries to access temporary pass that doesn't belong to him/her
            if (
            $temporaryPass->passable_type !== $user->getMorphClass() ||
            $temporaryPass->passable_id !== $user->id
            ) {
                abort(403, 'Unauthorized');
            }

            $temporaryPass->load('passable');

        } else if (Auth::guard('guest')->check()) {
            $guest = Auth::guard('guest')->user();

            if ($temporaryPass->passable_type !== $guest->getMorphClass() || $temporaryPass->passable_id !== $guest->id) {
                abort(403, 'Unauthorized');
            }

            $temporaryPass->load('passable');

        } else {
            abort(403, 'Unauthorized');
        }

        return view('test.passes.show', ['pass' => $temporaryPass]);
    }



    /**
     * Update the specified resource in storage (UPDATE).
     */
    public function update(Request $request, TemporaryPass $temporaryPass)
    {
        // Only Admins can update passes (Approve/Reject)
        if (!Auth::guard('web')->check()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status' => 'sometimes|in:pending,approved,rejected',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $temporaryPass->update($request->only(['status', 'valid_from', 'valid_until']));

        // Get details of user who applied
        $user = $temporaryPass->passable;
        $username = $user->name;
        $recipient = $user->email;

        // If rejected, send email & exit
        if ($request->input('status') === "rejected") {
            $status = "rejected";
            // Send email notifying user
            Mail::to($recipient)
                ->send(new WelcomeMail($username, $status, null));
            return redirect()->route('passes.index')->with('success', 'Pass rejected, email sent!');
        }

        // If approved
        if ($request->input('status') === 'approved') {
            $temporaryPass->approved_by = Auth::guard('web')->id();
            $status = "approved";
            

            // Set validity period based on reason
            $now = now();
            switch($temporaryPass->reason_label) {

                // Lost ID - 1 week
                case 'lost_id':
                    $temporaryPass->valid_from = $now;
                    $temporaryPass->valid_until = $now->copy()->addWeek();
                    break;

                // Damaged Card - 1 week
                case 'damaged_card':
                    $temporaryPass->valid_from = $now;
                    $temporaryPass->valid_until = $now->copy()->addWeek();
                    break;

                // Campus event - 1 day
                case 'campus_event':
                    $temporaryPass->valid_from = $now;
                    $temporaryPass->valid_until = $now->copy()->addDay();
                    break;

                // Misplaced ID - 1 day
                case 'misplaced_id':
                    $temporaryPass->valid_from = $now;
                    $temporaryPass->valid_until = $now->copy()->addDay();
                    break;

                default:
                    // fallback in case reason is missing or different
                    $temporaryPass->valid_from = $now;
                    $temporaryPass->valid_until = $now->copy()->addDay();
                    break;
            }

            // Generate unique QR token
            $temporaryPass->qr_code_token = (String) Str::uuid();

            // Send email with QR code
            Mail::to($recipient)->send(new WelcomeMail($username, $status));
        }

        $temporaryPass->save();
        

        // Redirect to appropriate route
        return redirect()->route('passes.index')->with('success', 'Successfully updated, email sent to user, email sent to user!');
    }

    /**
     * Remove the specified resource from storage (DELETE).
     */
    public function destroy(TemporaryPass $temporaryPass)
    {
        // Only Admins can delete passes
        if (!Auth::guard('web')->check()) {
            abort(403, 'Unauthorized');
        }

        $temporaryPass->delete();

        // Redirect to appropriate route
        return redirect()->route('passes.index')->with('success', 'Successfully deleted!');
    }
}