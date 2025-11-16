<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TemporaryPassController;
use App\Models\Guest;
use App\Models\TemporaryPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

// Root redirects to login choice
Route::get('/', fn () => redirect()->route('login.choice'));

// Login choice page
Route::view('/login/choice', 'auth.login-choice')->name('login.choice');

Route::get('/login', function () {
    return redirect()->route('login.choice');
})->name('login');

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

// Student login form
Route::get('/login/student', [StudentController::class, 'showLogin'])->name('student.login.form');
Route::post('/login/student', [StudentController::class, 'login'])->name('student.login');

// Protected student routes
Route::middleware('auth:university')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

    Route::middleware('auth:university')->group(function () {
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    });

    Route::view('/applications/create', 'application.create')->name('application.create');
    Route::post('/applications', [StudentController::class, 'applyTemporaryPass'])
        ->name('application.store');

    Route::get('/applications/{application}', function (TemporaryPass $application) {
        return view('application.show', ['application' => $application]);
    })->name('application.show');

    Route::get('/report/lost-id', function () {
        return view('report.lost-id');
    })->name('report.lost.id');

    Route::post('/report/lost-id', [StudentController::class, 'storeLostId'])
        ->name('report.lost.id.store');

    Route::post('/student/logout', [StudentController::class, 'logout'])->name('student.logout');
});

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

// Guest login form
Route::get('/login/guest', function () {
    return view('auth.guest-login'); // Blade for guest login
})->name('guest.login');

// Guest profile
Route::middleware('auth:guest')->group(function () {
    Route::get('/guest/profile', [GuestController::class, 'profile'])->name('guest.profile');
});

// Guest login submission
Route::post('/login/guest', function (Request $request) {
    // For testing, log in the first guest
    $guest = Guest::where('email', $request->email)->first();
    if ($guest) {
        Auth::guard('guest')->login($guest);
    }

    return redirect()->route('guest.dashboard')->with('status', 'Welcome back, Guest.');
})->name('guest.login.submit');

// Guest protected routes
Route::middleware('auth:guest')->group(function () {

    // Guest dashboard
    Route::get('/guest/dashboard', function () {
        $passes = \App\Models\TemporaryPass::where('passable_type', Guest::class)
            ->where('passable_id', auth('guest')->id())
            ->latest()
            ->get();

        return view('guest.dashboard', compact('passes'));
    })->name('guest.dashboard');

    // Guest logout
    Route::post('/guest/logout', function () {
        Auth::guard('guest')->logout();

        return redirect()->route('guest.login');
    })->name('guest.logout');

    // Show form to create a guest application
    Route::get('/guest/applications/create', function () {
        $guest = auth('guest')->user();

        return view('guest.application-create', compact('guest'));
    })->name('guest.application.create');

    // Store the guest application
    Route::post('/guest/applications', [TemporaryPassController::class, 'store'])
        ->name('guest.application.store');

    // View a specific guest application
    Route::get('/guest/applications/{application}', function (\App\Models\TemporaryPass $application) {
        if ($application->passable_id !== auth('guest')->id()) {
            abort(403, 'Unauthorized');
        }

        return view('guest.application-show', compact('application'));
    })->name('guest.application.show');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Login
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin routes (protected)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('applications', [AdminController::class, 'manageApplications'])->name('admin.applications.manage');
    Route::get('applications/{application}', [AdminController::class, 'reviewApplication'])->name('admin.applications.review');
    Route::post('applications/{application}/approve', [AdminController::class, 'approveApplication'])->name('admin.applications.approve');
    Route::post('applications/{application}/reject', [AdminController::class, 'rejectApplication'])->name('admin.applications.reject');

    Route::get('passes/expired', [AdminController::class, 'expiredPasses'])->name('passes.expired');
    Route::get('admin/reports/lost-id', [AdminController::class, 'lostIdReports'])->name('admin.reports.lost.id');

});
