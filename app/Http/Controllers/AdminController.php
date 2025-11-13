<?php

namespace App\Http\Controllers;

use App\Models\TemporaryPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show the admin login screen.
     */
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Handle the admin sign in attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    /**
     * Log out of the admin area.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Primary admin dashboard with metrics.
     */
    public function dashboard()
    {
        $this->ensureAdmin();

        $metrics = [
            'pending' => TemporaryPass::where('status', 'pending')->count(),
            'approved' => TemporaryPass::where('status', 'approved')->count(),
            'rejected' => TemporaryPass::where('status', 'rejected')->count(),
            'expired' => TemporaryPass::whereNotNull('valid_until')
                ->where('valid_until', '<', now())
                ->count(),
        ];

        $recentPasses = TemporaryPass::with(['passable'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'metrics' => $metrics,
            'passes' => $recentPasses,
        ]);
    }

    /**
     * Manage applications with a status filter.
     */
    public function applicationsManage(Request $request)
    {
        $this->ensureAdmin();

        $filter = ucfirst(strtolower($request->query('status', 'Pending')));
        $query = TemporaryPass::with('passable')->latest();

        if ($filter !== 'All') {
            $query->where('status', strtolower($filter));
        }

        $applications = $query->get();

        return view('admin.applications.manage', [
            'applications' => $applications,
            'currentFilter' => $filter,
        ]);
    }

    /**
     * Review a single application.
     */
    public function applicationsReview(TemporaryPass $application)
    {
        $this->ensureAdmin();

        $application->load(['passable', 'approver']);
        $application->ensureQrCodeAssets();

        return view('admin.applications.review', [
            'application' => $application,
        ]);
    }

    /**
     * View expired passes.
     */
    public function passesExpired()
    {
        $this->ensureAdmin();

        $expiredPasses = TemporaryPass::with('passable')
            ->whereNotNull('valid_until')
            ->where('valid_until', '<', now())
            ->orderByDesc('valid_until')
            ->get();

        return view('admin.passes.expired', [
            'expiredPasses' => $expiredPasses,
        ]);
    }

    /**
     * Ensure the current user is an authenticated admin.
     */
    private function ensureAdmin(): void
    {
        abort_unless(Auth::guard('web')->check(), 403, 'Unauthorized');
    }
}
