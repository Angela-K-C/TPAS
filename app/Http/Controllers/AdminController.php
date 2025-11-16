<?php

namespace App\Http\Controllers;

use App\Models\TemporaryPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Show login form
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            // Admin already logged in, redirect
            return redirect()->route('admin.dashboard')->with('info', 'You are already logged in as Admin.');
        }

        return view('admin.login');
    }

    // Logging in
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Logout method
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    // Admin dashboard
    public function dashboard()
    {
        $passes = TemporaryPass::latest()->take(5)->get();

        return view('admin.dashboard', compact('passes'));
    }

    // List all applications with optional status filter
    public function manageApplications(Request $request)
    {
        $status = $request->get('status', 'All');

        $applications = $status === 'All'
            ? TemporaryPass::latest()->get()
            : TemporaryPass::where('status', $status)->latest()->get();

        return view('admin.applications.manage', [
            'applications' => $applications,
            'currentFilter' => $status,
        ]);
    }

    // Show single application for review
    public function reviewApplication(TemporaryPass $application)
    {
        return view('admin.applications.review', compact('application'));
    }

    // Approve application
    public function approveApplication(Request $request, TemporaryPass $application)
    {
        $application->update([
            'status' => 'Approved',
            'approved_by' => Auth::guard('admin')->user()->name,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.admin.applications.manage')
            ->with('success', 'Application approved successfully.');
    }

    // Reject application
    public function rejectApplication(Request $request, TemporaryPass $application)
    {
        $application->update([
            'status' => 'Rejected',
            'approved_by' => Auth::guard('admin')->user()->name,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.admin.applications.manage')
            ->with('error', 'Application rejected.');
    }

    public function expiredPasses()
    {
        $expiredPasses = TemporaryPass::where('valid_until', '<', now())->latest()->get();

        return view('admin.passes.expired', compact('expiredPasses'));
    }

    public function lostIdReports()
    {
        // Return a view for lost ID reports, even if it's empty for now
        return view('admin.reports.lost-id');
    }
}
