<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\WelcomeMail;
use App\Models\TemporaryPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemporaryPassController extends Controller
{
    /**
     * Show the application form.
     */
    public function create()
    {
        if (! Auth::guard('university')->check() && ! Auth::guard('guest')->check()) {
            abort(403, 'Unauthorized');
        }

        [
            'userLabel' => $userLabel,
            'logoutRoute' => $logoutRoute,
        ] = $this->resolveLayoutContext();

        $applicant = Auth::guard('university')->user() ?? Auth::guard('guest')->user();

        return view('passes.create', [
            'reasonLabels' => TemporaryPass::reasonLabels(),
            'userLabel' => $userLabel,
            'logoutRoute' => $logoutRoute,
            'applicant' => $applicant,
        ]);
    }

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

        [
            'userLabel' => $userLabel,
            'logoutRoute' => $logoutRoute,
        ] = $this->resolveLayoutContext();

        $canApply = Auth::guard('university')->check() || Auth::guard('guest')->check();
        $isAdmin = Auth::guard('web')->check();

        return view('passes.index', [
            'passes' => $passes,
            'userLabel' => $userLabel,
            'logoutRoute' => $logoutRoute,
            'canApply' => $canApply,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Apply for a new temporary pass (Student/Guest)
     */
    public function store(Request $request) 
    {
        if (!Auth::guard('university')->check() && !Auth::guard('guest')->check()) {
            abort(403, 'Unauthorized');
        }

        $applicant = Auth::guard('university')->user() ?? Auth::guard('guest')->user();

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'pass_type' => 'nullable|string|max:255',
            'visitor_name' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'host_name' => 'nullable|string|max:255',
            'host_department' => 'nullable|string|max:255',
            'purpose' => 'nullable|string',
            'details' => 'nullable|string',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $pass = new TemporaryPass();
        $pass->fill($validated);
        $pass->status = 'pending';

        $message = 'Temporary pass application submitted!';
        $existingPass = TemporaryPass::existingNonRejectedFor($applicant);

        if (Auth::guard('university')->check()) {
            $student = Auth::guard('university')->user();
            $pass->passable()->associate($student);
            $pass->visitor_name = $pass->visitor_name ?: $student->name;
            $pass->email = $pass->email ?: $student->email;
            $pass->national_id = $pass->national_id ?: $student->admission_number;

            if ($existingPass) {
                $pass->status = 'rejected';
                $message = 'Your existing application is still active. Apply physically for a temporary pass.';

                $pass->save();
                $pass->ensureQrCodeAssets();

                if ($pass->email) {
                    Mail::to($pass->email)->send(new WelcomeMail($pass->visitor_name ?? $student->name, 'rejected', $pass));
                    $this->recordStatusEmail($pass, $pass->email, 'rejected');
                }

                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'application' => 'You already have an application (#' . $existingPass->id . ') with status "' . $existingPass->status . '". Apply physically at the security office for a temporary pass.',
                    ]);
            }

            $pass->status = 'approved';
            $message = 'Temporary pass approved automatically.';
        }

        elseif (Auth::guard('guest')->check()) {
            $guest = Auth::guard('guest')->user();
            $pass->passable()->associate($guest);
            $pass->visitor_name = $pass->visitor_name ?: $guest->name;
            $pass->email = $pass->email ?: $guest->email;
            $pass->phone = $pass->phone ?: $guest->phone;

            if ($existingPass) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'application' => 'You already have an application (#' . $existingPass->id . ') with status "' . $existingPass->status . '". Wait until it expires or apply physically at the security office.',
                    ]);
            }
        }

        $pass->save();
        $pass->ensureQrCodeAssets();

        if ($pass->status === 'approved' && $pass->email) {
            $recipient = $pass->email;
            $username = $pass->visitor_name ?? $applicant->name ?? 'User';
            Mail::to($recipient)->send(new WelcomeMail($username, 'approved', $pass));
            $this->recordStatusEmail($pass, $recipient, 'approved');
        }

        return redirect()->route('passes.index')->with('success', $message);
    }

    /**
     * Display the specified resource (READ one).
     */
    public function show(TemporaryPass $temporaryPass)
    {
        $this->assertViewerCanAccess($temporaryPass);
        $temporaryPass->load('passable', 'approver');
        $temporaryPass->ensureQrCodeAssets();

        [
            'userLabel' => $userLabel,
            'logoutRoute' => $logoutRoute,
        ] = $this->resolveLayoutContext();

        $isAdmin = Auth::guard('web')->check();

        return view('passes.show', [
            'pass' => $temporaryPass,
            'userLabel' => $userLabel,
            'logoutRoute' => $logoutRoute,
            'isAdmin' => $isAdmin,
        ]);
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
        $redirectRoute = route('admin.applications.review', ['application' => $temporaryPass->id]);

        // If rejected, send email & exit
        if ($request->input('status') === "rejected") {
            $status = "rejected";
            // Send email notifying user
            Mail::to($recipient)->send(new WelcomeMail($username, $status, $temporaryPass));
            $this->recordStatusEmail($temporaryPass, $recipient, $status);

            // Redirect to appropriate route
            return redirect($redirectRoute)->with('success', 'Pass rejected, email sent!');
        }

        // If approved
        if ($request->input('status') === 'approved') {
            $temporaryPass->approved_by = Auth::guard('web')->id();
            $status = "approved";
            

            // Set validity period based on reason
            $now = now();
            switch($temporaryPass->reason) {

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
            Mail::to($recipient)->send(new WelcomeMail($username, $status, $temporaryPass));
            $this->recordStatusEmail($temporaryPass, $recipient, $status);
        }

        $temporaryPass->save();

        // Redirect to appropriate route
        return redirect($redirectRoute)->with('success', 'Application updated. Email sent to the applicant.');
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
        return redirect()->route('admin.applications.manage')->with('success', 'Application removed successfully.');
    }

    /**
     * Serve the QR code image for the pass (Admins/Owners only).
     */
    public function qrCodeImage(TemporaryPass $temporaryPass)
    {
        $this->assertViewerCanAccess($temporaryPass);
        $temporaryPass->ensureQrCodeAssets();

        if (! $temporaryPass->qr_code_path) {
            abort(404, 'QR code unavailable.');
        }

        $path = Storage::disk('public')->path($temporaryPass->qr_code_path);

        if (! is_file($path)) {
            $temporaryPass->generateQrCodeImage();
        }

        return response()->file(
            $path,
            [
                'Content-Type' => 'image/svg+xml',
                'Cache-Control' => 'public, max-age=604800',
            ]
        );
    }

    /**
     * Download the QR code as a PDF file.
     */
    public function qrCodePdf(TemporaryPass $temporaryPass)
    {
        $this->assertViewerCanAccess($temporaryPass);
        $temporaryPass->ensureQrCodeAssets();

        if (! $temporaryPass->qr_code_path || ! Storage::disk('public')->exists($temporaryPass->qr_code_path)) {
            abort(404, 'QR code unavailable.');
        }

        $svg = Storage::disk('public')->get($temporaryPass->qr_code_path);
        $qrDataUri = 'data:image/svg+xml;base64,' . base64_encode($svg);
        $reference = strtoupper(substr($temporaryPass->qr_code_token ?? (string) $temporaryPass->id, 0, 8));

        $pdf = Pdf::loadView('passes.qr-pdf', [
            'pass' => $temporaryPass,
            'qrDataUri' => $qrDataUri,
            'reference' => $reference,
        ]);

        return $pdf->download("temporary-pass-{$temporaryPass->id}.pdf");
    }

    /**
     * Public verification endpoint embedded inside the QR payload.
     */
    public function verifyByToken(string $token)
    {
        $pass = TemporaryPass::with('passable')
            ->where('qr_code_token', $token)
            ->firstOrFail();

        return response()->json([
            'found' => true,
            'status' => $pass->status,
            'reason' => $pass->reason_label,
            'pass_reference' => strtoupper(substr($pass->qr_code_token, 0, 8)),
            'holder_name' => $pass->passable?->name,
            'holder_email' => $pass->passable?->email,
            'pass_type' => class_basename($pass->passable_type),
            'valid_from' => optional($pass->valid_from)?->toIso8601String(),
            'valid_until' => optional($pass->valid_until)?->toIso8601String(),
            'qr_token' => $pass->qr_code_token,
        ]);
    }

    /**
     * Persist an email log entry after notifying the applicant.
     */
    private function recordStatusEmail(TemporaryPass $temporaryPass, string $recipient, string $status): void
    {
        $subject = "Temporary Pass Application {$status}";
        $temporaryPass->logEmail($recipient, $subject, 'sent');
    }

    /**
     * Gate access so only admins or pass owners can interact with the resource.
     */
    private function assertViewerCanAccess(TemporaryPass $temporaryPass): void
    {
        if (Auth::guard('web')->check()) {
            return;
        }

        if (Auth::guard('university')->check()) {
            $user = Auth::guard('university')->user();

            if ($temporaryPass->passable_type === $user->getMorphClass()
                && $temporaryPass->passable_id === $user->id) {
                return;
            }
        }

        if (Auth::guard('guest')->check()) {
            $guest = Auth::guard('guest')->user();

            if ($temporaryPass->passable_type === $guest->getMorphClass()
                && $temporaryPass->passable_id === $guest->id) {
                return;
            }
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Determine the layout labels/routes for the authenticated guard.
     *
     * @return array{userLabel: string, logoutRoute: string}
     */
    private function resolveLayoutContext(): array
    {
        if (Auth::guard('web')->check()) {
            $admin = Auth::guard('web')->user();

            return [
                'userLabel' => $admin->name ?? 'Admin',
                'logoutRoute' => route('admin.logout'),
            ];
        }

        if (Auth::guard('university')->check()) {
            $student = Auth::guard('university')->user();

            return [
                'userLabel' => $student->name ?? 'Student',
                'logoutRoute' => route('student.logout'),
            ];
        }

        if (Auth::guard('guest')->check()) {
            $guest = Auth::guard('guest')->user();

            return [
                'userLabel' => $guest->name ?? 'Guest',
                'logoutRoute' => route('guest.logout'),
            ];
        }

        return [
            'userLabel' => 'User',
            'logoutRoute' => route('logout'),
        ];
    }
}
