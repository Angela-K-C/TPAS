<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\TemporaryPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // Show login form
    public function showLogin()
    {
        if (Auth::guard('university')->check()) {
            // Student already logged in, redirect
            // @TODO: Change Redirect Location
            return redirect()->route('dashboard')->with('info', 'You are already logged in as Student.');
        }

        return view('auth.student-login');
    }

    // Handle login form submission
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('university')->attempt($credentials)) {
            // Login successful
            // @TODO: Change Redirect Location
            return redirect()->route('dashboard')
                ->with('success', 'Welcome back, '.Auth::guard('university')->user()->name);
        }

        // Login failed
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Show form for applying temporary pass
    public function showTemporaryPassForm()
    {

        $student = Auth::guard('university')->user();

        return view('application.create', [
            'user' => $student,
            'type' => 'student',
        ]);
    }

    // Handle form submission for temporary pass
    public function applyTemporaryPass(Request $request)
    {
        $student = Auth::guard('university')->user();

        $request->validate([
            'pass_type' => 'required|string|max:255',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'reason' => 'required|string|max:500',
        ]);

        $pass = new TemporaryPass([
            'visitor_name' => $student->name,
            'email' => $student->email,
            'national_id' => $student->admission_number, // if you store it like this
            'reason' => $request->reason,
            'valid_from' => $request->date_from,
            'valid_until' => $request->date_to,
            'status' => 'pending',
        ]);

        $pass->passable()->associate($student);
        $pass->save();

        return redirect()->route('dashboard')
            ->with('success', 'Temporary pass submitted successfully.');
    }

    // Logout method
    public function logout()
    {
        Auth::guard('university')->logout();

        // @TODO: Change Redirect Location
        return redirect()->route('login.choice')->with('success', 'Logged out successfully!');
    }

    // Dashboard method
    public function dashboard()
    {
        $student = Auth::guard('university')->user();

        // Normal temporary passes (excluding lost ID)
        $passes = TemporaryPass::where('passable_type', Student::class)
            ->where('passable_id', $student->id)
            ->where('reason', '!=', 'lost_id')
            ->latest()
            ->get();

        // Lost ID reports
        $lostIds = TemporaryPass::where('passable_type', Student::class)
            ->where('passable_id', $student->id)
            ->where('reason', 'lost_id')
            ->latest()
            ->get();

        return view('dashboard', compact('passes', 'lostIds'));
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

    public function profile()
    {
        $student = Auth::guard('university')->user();

        return view('profile', [
            'user' => $student,
            'type' => 'student', // optional, in case you want conditional UI
            'logoutRoute' => route('student.logout'),
        ]);
    }

    public function applyPass(Request $request, $studentId)
    {
        $request->validate([
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
        ]);

        $student = Student::findOrFail($studentId);

        $student->passes()->create([
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Pass application submitted successfully!');
    }
}
