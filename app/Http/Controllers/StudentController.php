<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // Show login form
    public function showLogin() {
        if (Auth::guard('university')->check()) {
            // Student already logged in, redirect
            // @TODO: Change Redirect Location
            return redirect()->route('test.home')->with('info', 'You are already logged in as Student.');
        }

        return view('test.student');
    }

    // Handle login form submission
    public function login(Request $request) {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('university')->attempt($credentials)) {
            // Login successful
            // @TODO: Change Redirect Location
            return redirect()->route('test.home')
                ->with('success', 'Student logged in successfully!');
        }

        // Login failed
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Logout method
    public function logout()
    {
        Auth::guard('university')->logout();
        // @TODO: Change Redirect Location
        return redirect()->route('test.login')->with('success', 'Logged out successfully!');
    }
}
