<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Student;
use App\Models\TemporaryPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        } elseif (Auth::guard('university')->check()) {
            $user = Auth::guard('university')->user();

            $passes = TemporaryPass::where('passable_type', $user->getMorphClass())
                ->where('passable_id', $user->id)
                ->latest()
                ->get();

        } elseif (Auth::guard('guest')->check()) {
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
        if (Auth::guard('university')->check()) {
            $student = Auth::guard('university')->user();

            $request->validate([
                'purpose' => 'required|string|max:500',
            ]);

            $pass = TemporaryPass::create([
                'visitor_name' => $student->name,
                'email' => $student->email,
                'national_id' => null,
                'phone' => null,
                'host_name' => null,
                'host_department' => null,
                'reason' => $request->purpose,
                'passable_type' => Student::class,
                'passable_id' => $student->id,
                'status' => 'pending',
            ]);

            return redirect()->route('dashboard')->with('success', 'Temporary pass submitted.');
        }

        if (Auth::guard('guest')->check()) {
            $guest = Auth::guard('guest')->user();

            $validated = $request->validate([
                'visitor_name' => 'required|string|max:255',
                'national_id' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'host_name' => 'required|string|max:255',
                'host_department' => 'required|string|max:255',
                'visit_start' => 'required|date',
                'visit_end' => 'required|date|after_or_equal:visit_start',
                'purpose' => 'required|string|max:500',
            ]);

            $pass = new TemporaryPass($validated);
            $pass->reason = $validated['purpose'];
            $pass->passable()->associate($guest);
            $pass->save();

            return redirect()->route('guest.dashboard')
                ->with('success', 'Your visitor pass application has been submitted!');
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Display the specified resource (READ one).
     */
    public function show(TemporaryPass $temporaryPass)
    {
        if (Auth::guard('web')->check()) {
            $temporaryPass->load('passable');

        } elseif (Auth::guard('university')->check()) {
            $user = Auth::guard('university')->user();

            // User tries to access temporary pass that doesn't belong to him/her
            if (
                $temporaryPass->passable_type !== $user->getMorphClass() ||
                $temporaryPass->passable_id !== $user->id
            ) {
                abort(403, 'Unauthorized');
            }

            $temporaryPass->load('passable');

        } elseif (Auth::guard('guest')->check()) {
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
        if (! Auth::guard('web')->check()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status' => 'sometimes|in:pending,approved,rejected',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $temporaryPass->update($request->only(['status', 'valid_from', 'valid_until']));

        if ($request->input('status') === 'approved') {
            $temporaryPass->approved_by = Auth::guard('web')->id();

            // Set validity period based on reason
            $now = now();
            switch ($temporaryPass->reason_label) {

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
        }

        $temporaryPass->save();

        return redirect()->route('guest.dashboard')->with('success', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage (DELETE).
     */
    public function destroy(TemporaryPass $temporaryPass)
    {
        // Only Admins can delete passes
        if (! Auth::guard('web')->check()) {
            abort(403, 'Unauthorized');
        }

        $temporaryPass->delete();

        return redirect()->route('guest.dashboard')->with('success', 'Successfully deleted!');
    }

    public function manage()
    {
        $passes = TemporaryPass::all(); // add filters if needed

        return view('admin.applications.manage', compact('passes'));
    }

    public function review(TemporaryPass $application)
    {
        return view('admin.applications.review', compact('application'));
    }

    public function expired()
    {
        $passes = TemporaryPass::where('status', 'Expired')->get();

        return view('admin.passes.expired', compact('passes'));
    }
}
