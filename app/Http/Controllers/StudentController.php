<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\TemporaryPass;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StudentController extends Controller
{
    /**
     * Show the student login form.
     */
    public function showLoginForm()
    {
        if (Auth::guard('university')->check()) {
            return redirect()->route('dashboard')
                ->with('info', 'You are already logged in as Student.');
        }

        return view('auth.student-login');
    }

    /**
     * Handle student login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('university')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . Auth::guard('university')->user()->name);
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    /**
     * Log the student out.
     */
    public function logout()
    {
        Auth::guard('university')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('student.login')->with('status', 'Logged out successfully!');
    }

    /**
     * Student dashboard view showing applications and lost ID reports.
     */
    public function dashboard()
    {
        $student = Auth::guard('university')->user();

        $passes = TemporaryPass::where('passable_type', Student::class)
            ->where('passable_id', $student->id)
            ->where('reason', '!=', 'lost_id')
            ->latest()
            ->get();

        $lostIds = TemporaryPass::where('passable_type', Student::class)
            ->where('passable_id', $student->id)
            ->where('reason', 'lost_id')
            ->latest()
            ->get();

        return view('dashboard', compact('passes', 'lostIds'));
    }

    /**
     * Show the temporary pass form with current student details.
     */
    public function showTemporaryPassForm()
    {
        $student = Auth::guard('university')->user();

        return view('application.create', [
            'user' => $student,
            'type' => 'student',
        ]);
    }

    /**
     * Apply for a new temporary pass.
     */
    public function applyTemporaryPass(Request $request)
    {
        $student = Auth::guard('university')->user();

        $validated = $request->validate([
            'pass_type' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'reason' => 'required|string|max:500',
        ]);

        $existingPass = TemporaryPass::existingNonRejectedFor($student);
        if ($existingPass) {
            $rejectedPass = new TemporaryPass([
                'visitor_name' => $student->name,
                'email' => $student->email,
                'national_id' => $student->admission_number,
                'pass_type' => $validated['pass_type'],
                'reason' => $validated['reason'],
                'valid_from' => $validated['date_from'],
                'valid_until' => $validated['date_to'],
                'status' => 'rejected',
            ]);

            $rejectedPass->passable()->associate($student);
            $rejectedPass->save();
            $rejectedPass->ensureQrCodeAssets();

            if ($student->email) {
                Mail::to($student->email)->send(new WelcomeMail($student->name, 'rejected', $rejectedPass));
                $rejectedPass->logEmail($student->email, 'Temporary Pass Application rejected', 'sent');
            }

            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'application' => 'You already have an application (#' . $existingPass->id . ') with status "' . $existingPass->status . '". Apply physically at the security office for a temporary pass.',
                ]);
        }

        $pass = new TemporaryPass([
            'visitor_name' => $student->name,
            'email' => $student->email,
            'national_id' => $student->admission_number,
            'pass_type' => $validated['pass_type'],
            'reason' => $validated['reason'],
            'valid_from' => $validated['date_from'],
            'valid_until' => $validated['date_to'],
            'status' => 'approved',
        ]);

        $pass->passable()->associate($student);
        $pass->save();

        if ($student->email) {
            Mail::to($student->email)->send(new WelcomeMail($student->name, 'approved', $pass));
            $pass->logEmail($student->email, 'Temporary Pass Application approved', 'sent');
        }

        return redirect()->route('dashboard')
            ->with('success', 'Temporary pass approved automatically.');
    }

    /**
     * Handle reporting a lost ID.
     */
    public function storeLostId(Request $request)
    {
        $student = Auth::guard('university')->user();

        $request->validate([
            'lost_date' => 'required|date',
            'location' => 'required|string|max:255',
            'confirm_report' => 'required',
        ]);

        TemporaryPass::create([
            'passable_type' => Student::class,
            'passable_id' => $student->id,
            'visitor_name' => $student->name,
            'email' => $student->email,
            'national_id' => $student->admission_number,
            'reason' => 'lost_id',
            'valid_from' => $request->lost_date,
            'valid_until' => $request->lost_date,
            'status' => 'reported',
            'details' => $request->location,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Lost ID reported successfully.');
    }

    /**
     * Show the authenticated student's profile.
     */
    public function profile()
    {
        $student = Auth::guard('university')->user();

        return view('profile', [
            'user' => $student,
            'type' => 'student',
            'logoutRoute' => route('student.logout'),
        ]);
    }

    /**
     * Legacy helper used by seeder/tests to create passes.
     */
    public function applyPass(Request $request, $studentId)
    {
        $request->validate([
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
        ]);

        $student = Student::findOrFail($studentId);

        $createdPass = $student->passes()->create([
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'status' => 'approved',
        ]);

        if ($student->email) {
            Mail::to($student->email)->send(new WelcomeMail($student->name, 'approved', $createdPass));
            $createdPass->logEmail($student->email, 'Temporary Pass Application approved', 'sent');
        }

        return redirect()->back()->with('success', 'Pass approved automatically!');
    }
}
