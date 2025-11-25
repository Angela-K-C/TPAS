<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TemporaryPass;

class GuestController extends Controller
{
    // Show login form
    public function showLogin()
    {
        if (Auth::guard('guest')->check()) {
            // Admin already logged in, redirect
            return redirect()->route('guest.dashboard')->with('info', 'You are already logged in as Guest.');
        }

        return view('auth.guest-login');
    }

    // Login with only email (for guests)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if guest exists, else create
        $guest = Guest::where('email', $request->email)->first();
        if (!$guest) {
            $guest = Guest::create([
                'email' => $request->email,
                'name' => 'Guest', // or you can use $request->email as name
            ]);
        }

        // Log in the guest
        Auth::guard('guest')->login($guest);
        $request->session()->regenerate();

        // Store guest ID and email in session
        $request->session()->put('guest_id', $guest->id);
        $request->session()->put('guest_email', $guest->email);

        return redirect()->route('guest.dashboard')->with('success', 'Logged in successfully!');
    }

    // Logout method
    public function logout()
    {
        Auth::guard('guest')->logout();
        session()->invalidate();
        session()->regenerateToken();
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
            'host_department' => 'nullable|string',
            'reason' => 'nullable|string',
        ]);

        $guest = Guest::findOrFail($guestId);

        $guest->passes()->create([
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'national_id' => $request->national_id,
            'host_name' => $request->host_name,
            'host_department' => $request->host_department,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pass application submitted successfully!');
    }

    public function dashboard()
    {
        $guest = Auth::guard('guest')->user();
        $email = session('guest_email');
        $passes = [];
        if ($guest) {
            $email = $guest->email;
            // Get all passes for this guest
            $passes = $guest->passes()->orderBy('created_at', 'desc')->get();
        }

        return view('guest.dashboard', compact('passes', 'email'));
    }

    // Show application creation form
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
            'visitor_name' => 'required|string|max:255',
            'national_id' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'host_name' => 'nullable|string|max:255',
            'host_department' => 'nullable|string|max:255',
            'purpose' => 'nullable|string',
            'visit_start' => 'required|date',
            'visit_end' => 'required|date|after_or_equal:visit_start',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (!$guest) {
            return redirect()->route('guest.login')->withErrors(['error' => 'Guest not authenticated. Please log in again.']);
        }

        $existingPass = TemporaryPass::existingNonRejectedFor($guest);
        if ($existingPass) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'application' => 'You already have an application (#' . $existingPass->id . ') with status "' . $existingPass->status . '". Wait until it expires before applying again.',
                ]);
        }

        // Keep guest profile in sync with form details so future emails greet them correctly.

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $photoPath = $photo->store('guest-photos', 'public');
            $guest->profile_image_path = $photoPath;
        }

        $guest->fill([
            'name' => $request->visitor_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
        ])->save();

        TemporaryPass::create([
            'passable_type' => 'App\\Models\\Guest',
            'passable_id' => $guest->id,
            'visitor_name' => $request->visitor_name ?? $guest->name,
            'national_id' => $request->national_id,
            'email' => $request->email ?? $guest->email,
            'phone' => $request->phone ?? $guest->phone,
            'host_name' => $request->host_name,
            'host_department' => $request->host_department,
            'purpose' => $request->purpose,
            'reason' => $request->reason ?? 'Strathmore Visit',
            'valid_from' => $request->visit_start,
            'valid_until' => $request->visit_end,
            'status' => 'pending',
        ]);

        return redirect()->route('guest.dashboard')->with('success', 'Pass application submitted!');
    }

    // Show single application
    public function showApplication($id)
    {
        $pass = TemporaryPass::findOrFail($id);

        // Only let the owning guest view their pass details.
        $guest = Auth::guard('guest')->user();
        abort_unless($guest && $pass->passable_type === $guest->getMorphClass() && $pass->passable_id === $guest->id, 403);

        // Ensure QR assets exist so the detail view/email/admin all see the same code.
        $pass->ensureQrCodeAssets();

        return view('guest.application-show', [
            'application' => $pass,
        ]);
    }
}
