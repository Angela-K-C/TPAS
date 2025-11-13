<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TemporaryPassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\TemporaryPass;

/*
|--------------------------------------------------------------------------
| Frontend Routes (Main Application)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard',function(){
    $passes = TemporaryPass::latest()->take(10)->get();
    return view('dashboard',['passes'=>$passes]);
})->name('dashboard');



// Redirect root to login choice
Route::get('/', fn () => redirect()->route('login.choice'));

// Login choice page
Route::view('/login', 'auth.login-choice')->name('login.choice');

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::match(['get', 'post'], '/login/student', function (Request $request) {
    if ($request->isMethod('post')) {
        return redirect()->route('dashboard')->with('status', 'Welcome back, Student.');
    }
    return view('auth.student-login');
})->name('student.login');

Route::get('/dashboard',function(){
    $passes = TemporaryPass::latest()->take(30)->get();
    return view('dashboard',['passes'=>$passes]);
})->name('dashboard');

Route::view('/profile', 'profile')->name('profile');

Route::view('/applications/create', 'application.create')->name('application.create');
Route::post('/applications', fn (Request $request) => redirect()
    ->route('dashboard')
    ->with('status', 'Temporary pass submitted.')
)->name('application.store');

Route::get('/applications/{application}', function (TemporaryPass $application) {
    return view('application.show', ['application' => $application]);
})->name('application.show');

Route::view('/report/lost-id', 'report.lost-id')->name('report.lost.id');
Route::post('/report/lost-id', fn (Request $request) => redirect()
    ->route('dashboard')
    ->with('status', 'Lost ID reported.')
)->name('report.lost.id.store');

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::match(['get', 'post'], '/login/guest', function (Request $request) {
    if ($request->isMethod('post')) {
        return redirect()->route('guest.dashboard')->with('status', 'Welcome back, Guest.');
    }
    return view('auth.guest-login');
})->name('guest.login');

Route::get('/guest/dashboard', function () {
    $guest = auth('guest')->user();
    $passes = $guest
        ? TemporaryPass::where('passable_type', get_class($guest))
            ->where('passable_id', $guest->id)
            ->latest()
            ->get()
        : collect();

    return view('guest.dashboard', ['passes' => $passes]);
})->name('guest.dashboard');

Route::view('/guest/applications/create', 'guest.application-create')->name('guest.application.create');
Route::post('/guest/applications', fn (Request $request) => redirect()
    ->route('guest.dashboard')
    ->with('status', 'Visitor pass submitted.')
)->name('guest.application.store');

Route::get('/guest/applications/{application}', fn (string $application) =>
    view('guest.application-show')
)->name('guest.application.show');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Authentication
Route::view('/admin/login', 'admin.login')->name('admin.login');

// Dashboard
Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');

// Applications
Route::view('/admin/applications/manage', 'admin.applications.manage')->name('admin.applications.manage');
Route::view('/admin/applications/show', 'admin.applications.show')->name('admin.applications.show');

Route::get('/admin/applications/review/{application}', fn (string $application) =>
    view('admin.applications.review')
)->name('admin.applications.review');

Route::put('/admin/applications/review/{application}', function (string $application) {
    return redirect()
        ->route('admin.applications.review', ['application' => $application])
        ->with('status', 'Application updated.');
})->name('application.update');

// Passes and Reports
Route::view('/admin/passes/expired', 'admin.passes.expired')->name('admin.passes.expired');
Route::view('/admin/reports/lost-id', 'admin.reports.lost-id')->name('admin.reports.lost.id');

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', fn () => redirect()->route('login.choice'))->name('logout');
