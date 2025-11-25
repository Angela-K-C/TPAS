<?php

/**
 * Security bundle copy:
 * Handles login/logout for the dedicated `security` auth guard so it can be
 * dropped into another Laravel project without hunting down dependencies.
 */

namespace App\Http\Controllers;

use App\Models\SecurityStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('security')->check()) {
            return redirect()->route('security.verify');
        }

        return view('security.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('security')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var SecurityStaff $staff */
            $staff = Auth::guard('security')->user();
            $staff->forceFill(['last_login_at' => now()])->saveQuietly();

            return redirect()->intended(route('security.verify'));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('security')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('security.login');
    }
}
