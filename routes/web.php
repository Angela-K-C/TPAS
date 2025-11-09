<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemporaryPassController;

//The following routes were made by the backend team
/*
// Homepage
Route::get('/', function () {
    return view('test.home');
})->name('test.home');


// ================ Logged-in users (Admin or Student/Guest) =================
Route::middleware('auth:web,university,guest')->group(function () {

    // View all passes
    Route::get('/passes', [TemporaryPassController::class, 'index'])->name('passes.index');

    // Display Temporary pass application form
    Route::get('/passes/create', function() {
        return view('test.passes.create');
    })->name('passes.create');

    // Show single temporary pass
    Route::get('/passes/{temporaryPass}', [TemporaryPassController::class, 'show'])->name('passes.show');

    // Submit temporary pass application
    Route::post('/passes', [TemporaryPassController::class, 'store'])->name('passes.store');
});


// ===================== Admin Functions Only =====================
Route::middleware('auth:web')->group(function() {
    // Rejecting and approving passes
    Route::put('/passes/{temporaryPass}', [TemporaryPassController::class, 'update'])->name('passes.update');

    // Delete pass
    Route::delete('/passes/{temporaryPass}', [TemporaryPassController::class, 'destroy'])->name('passes.destroy');


});



// ==================== Login routes ===============================
Route::get('/login', function() {
    if (Auth::guard('web')->check() | Auth::guard('university')->check() | Auth::guard('guest')->check()) {
        // Already logged in, redirect
        return redirect()->route('test.home')->with('info', 'You are already logged in!');
    }

    return view('test.login');
})->name('test.login');

// Login forms
Route::get('/login/admin', [AdminController::class, 'showLogin'])->name('admin.login');
Route::get('/login/student', [StudentController::class, 'showLogin'])->name('student.login');
Route::get('/login/guest', [GuestController::class, 'showLogin'])->name('guest.login');

Route::post('/login/admin', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/login/student', [StudentController::class, 'login'])->name('student.login.submit');
Route::post('/login/guest', [GuestController::class, 'login'])->name('guest.login.submit');

// Logout
Route::post('/logout/admin', [AdminController::class, 'logout'])->name('admin.logout');
Route::post('/logout/student', [StudentController::class, 'logout'])->name('student.logout');
Route::post('/logout/guest', [GuestController::class, 'logout'])->name('guest.logout');
*/



//The following routes were made by the frontend team
//Group 5's job is to configure these routes appropriately to match our codebase as they integrate the backend and the frontend
Route::get('/', fn () => redirect()->route('login.choice'));

Route::view('/login', 'auth.login-choice')->name('login.choice');

Route::match(['get', 'post'], '/login/student', function (Request $request) {
    if ($request->isMethod('post')) {
        return redirect()->route('dashboard')->with('status', 'Welcome back, Student.');
    }

    return view('auth.student-login');
})->name('student.login');

Route::match(['get', 'post'], '/login/guest', function (Request $request) {
    if ($request->isMethod('post')) {
        return redirect()->route('guest.dashboard')->with('status', 'Welcome back, Guest.');
    }

    return view('auth.guest-login');
})->name('guest.login');

Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/profile', 'profile')->name('profile');

Route::view('/applications/create', 'application.create')->name('application.create');
Route::post('/applications', fn (Request $request) => redirect()->route('dashboard')->with('status', 'Temporary pass submitted.'))->name('application.store');

Route::get('/applications/{application}', fn (string $application) => view('application.show'))->name('application.show');

Route::view('/guest/dashboard', 'guest.dashboard')->name('guest.dashboard');
Route::view('/guest/applications/create', 'guest.application-create')->name('guest.application.create');
Route::post('/guest/applications', fn (Request $request) => redirect()->route('guest.dashboard')->with('status', 'Visitor pass submitted.'))->name('guest.application.store');
Route::get('/guest/applications/{application}', fn (string $application) => view('guest.application-show'))->name('guest.application.show');

Route::view('/report/lost-id', 'report.lost-id')->name('report.lost.id');
Route::post('/report/lost-id', fn (Request $request) => redirect()->route('dashboard')->with('status', 'Lost ID reported.'))->name('report.lost.id.store');

Route::post('/logout', fn () => redirect()->route('login.choice'))->name('logout');
