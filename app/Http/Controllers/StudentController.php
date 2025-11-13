<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        if (Auth::guard('university')->check()) {
            return redirect()->route('dashboard');
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
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with('status', 'Welcome back!');
        }

        // Login failed
        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    // Logout method
    public function logout()
    {
        Auth::guard('university')->logout();
        return redirect()->route('student.login')->with('status', 'Logged out successfully!');
    }
}
