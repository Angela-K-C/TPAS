<?php

namespace App\Http\Controllers;

use App\Models\TemporaryPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    /**
     * Show reported lost IDs from students, with optional search.
     */
    public function reportsLostId(Request $request)
    {
        $this->ensureAdmin();

        $search = trim((string) $request->query('q', ''));

        $query = TemporaryPass::with('passable')
            ->where('reason', 'lost_id')
            ->orderByDesc('created_at');

        if ($search !== '') {
            $query->where(function ($inner) use ($search) {
                $inner->where('visitor_name', 'like', '%' . $search . '%')
                    ->orWhere('national_id', 'like', '%' . $search . '%')
                    ->orWhere('details', 'like', '%' . $search . '%');
            });
        }

        $reports = $query->get();

        return view('admin.reports.lost-id', [
            'reports' => $reports,
            'search' => $search,
        ]);
    }
    /**
     * Show the admin login screen.
     */
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard')->with('info', 'You are already logged in as Admin.');
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

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Logged in successfully!');
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

        return redirect()->route('admin.login')->with('status', 'Logged out successfully.');
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

        $application->load(['passable', 'approver', 'auditLogs.admin']);
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
     * Reset a temporary pass so the user can apply again.
     */
    public function resetPass(Request $request, TemporaryPass $temporaryPass)
    {
        $this->ensureAdmin();

        $expiredAt = Carbon::now()->subMinute();
        $resetNote = 'Reset by admin on ' . $expiredAt->format('Y-m-d H:i') . ' — pass no longer usable.';

        $temporaryPass->forceFill([
            'status' => 'rejected',
            'valid_until' => $expiredAt,
            'details' => trim(($temporaryPass->details ? $temporaryPass->details . ' | ' : '') . $resetNote),
        ])->save();

        return back()->with('success', 'Temporary pass reset; the user can reapply.');
    }

    /**
     * Reset passes by admission number or email in one action.
     */
    public function resetPassByIdentifier(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'identifier' => ['required', 'string'],
        ]);

        $identifier = trim($data['identifier']);
        $expiredAt = Carbon::now()->subMinute();
        $resetNote = 'Reset by admin on ' . $expiredAt->format('Y-m-d H:i') . ' — pass no longer usable.';

        $updated = 0;

        TemporaryPass::where(function ($query) use ($identifier) {
                $query->where('email', $identifier)
                    ->orWhere('national_id', $identifier);
            })
            ->where('status', '!=', 'rejected')
            ->each(function (TemporaryPass $pass) use ($expiredAt, $resetNote, &$updated) {
                $pass->forceFill([
                    'status' => 'rejected',
                    'valid_until' => $expiredAt,
                    'details' => trim(($pass->details ? $pass->details . ' | ' : '') . $resetNote),
                ])->save();
                $updated++;
            });

        if ($updated === 0) {
            return back()->with('status', 'No matching passes found for that admission number or email.');
        }

        return back()->with('success', "Reset {$updated} pass(es); the user can reapply now.");
    }

    /**
     * Ensure the current user is an authenticated admin.
     */
    private function ensureAdmin(): void
    {
        abort_unless(Auth::guard('web')->check(), 403, 'Unauthorized');
    }
}
