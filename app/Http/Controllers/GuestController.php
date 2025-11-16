<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    // Show login form
    public function showLogin()
    {
        if (Auth::guard('guest')->check()) {
            // Admin already logged in, redirect
            return redirect()->route('guest.dashboard')->with('info', 'You are already logged in as Guest.');
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
            $request->session()->regenerate();

            return redirect()->route('guest.dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['email' => 'Guest not found.']);
    }

    // Logout method
    public function logout()
    {
        Auth::guard('guest')->logout();

        return redirect()->route('guest.login')->with('success', 'Logged out successfully!');
    }

    public function profile()
    {
        $guest = Auth::guard('guest')->user();

        return view('profile', [
            'user' => $guest,
            'type' => 'guest',
            'logoutRoute' => route('guest.logout'),
        ]);
    }

    public function applyPass(Request $request, $guestId)
    {
        $request->validate([
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'national_id' => 'nullable|string',
            'host_name' => 'nullable|string',
            'mentoring_department' => 'nullable|string',
            'reason' => 'nullable|string',
        ]);

        $guest = Guest::findOrFail($guestId);

        $guest->passes()->create([
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'national_id' => $request->national_id,
            'host_name' => $request->host_name,
            'mentoring_department' => $request->mentoring_department,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Pass application submitted successfully!');
    }

    public function dashboard()
    {
        $guest = Auth::guard('guest')->user();

        // Get all passes for this guest
        $passes = $guest->passes()->orderBy('created_at', 'desc')->get();

        return view('guest.dashboard', compact('passes'));
    }

    p// Show application creation form
    public function createApplication()
    {
        $guest = Auth::guard('guest')->user();
        return view('guest.application-create', compact('guest'));
    }

    // Store application
    public function storeApplication(Request $request)
    {
        $guest = Auth::guard('guest')->user();

        $request->validate([
            'national_id' => 'nullable|string',
            'host_name' => 'nullable|string',
            'host_department' => 'nullable|string',
            'purpose' => 'nullable|string',
            'visit_start' => 'required|date',
            'visit_end' => 'required|date|after_or_equal:visit_start',
        ]);

        TemporaryPass::create([
            'passable_type' => 'App\Models\Guest',
            'passable_id' => $guest->id,
            'national_id' => $request->national_id,
            'host_name' => $request->host_name,
            'host_department' => $request->host_department,
            'purpose' => $request->purpose,
            'valid_from' => $request->visit_start,
            'valid_until' => $request->visit_end,
            'status' => 'Pending',
        ]);

        return redirect()->route('guest.dashboard')->with('success', 'Pass application submitted!');
    }

    // Show single application
    public function showApplication($id)
    {
        $pass = TemporaryPass::findOrFail($id);

        return view('guest.application-show', compact('pass'));
    }
}