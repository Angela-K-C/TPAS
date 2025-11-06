<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    // Show login form 
    public function showLogin() {
        if (Auth::guard('guest')->check()) {
            // Admin already logged in, redirect
            return redirect()->route('test.home')->with('info', 'You are already logged in as Guest.');
        }
        
        return view('test.guest');
    }
    

    // Login with only email (for guests)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:guests,email',
        ]);

        // Find guest by email
        $guest = Guest::where('email', $request->email)->first();

        if ($guest) {
            // Log them in manually without password
            Auth::guard('guest')->login($guest);

            return redirect()->route('test.home')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['email' => 'Guest not found.']);
    }

    // Logout method
    public function logout()
    {
        Auth::guard('guest')->logout();

        return redirect()->route('test.login')->with('success', 'Logged out successfully!');
    }

}
