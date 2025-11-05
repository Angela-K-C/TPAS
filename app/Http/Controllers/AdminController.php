<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    // Show login form
    public function showLogin() {
        if (Auth::guard('web')->check()) {
            // Admin already logged in, redirect
            // @TODO: Change Redirect Location
            return redirect()->route('test.home')->with('info', 'You are already logged in as Admin.');
        }
        
        return view('test.admin');
    }

    // Logging in
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        // @TODO: Change Redirect Location
        if (Auth::attempt($credentials)) {
            return redirect()->route('test.home')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Logout method
    public function logout()
    {
        Auth::guard('web')->logout();

        // @TODO: Change Redirect Location
        return redirect()->route('test.login')->with('success', 'Logged out successfully!');
    }
}
