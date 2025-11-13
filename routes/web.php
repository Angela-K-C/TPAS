<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\SecurityAuthController;
use App\Http\Controllers\SecurityVerificationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TemporaryPassController;
use App\Models\TemporaryPass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes (Main Application)
|--------------------------------------------------------------------------
*/

// Redirect root to login choice
Route::get('/', fn () => redirect()->route('login.choice'));

// Login choice page
Route::view('/login', 'auth.login-choice')->name('login.choice');

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest:university')->group(function () {
    Route::get('/login/student', [StudentController::class, 'showLoginForm'])->name('student.login');
    Route::post('/login/student', [StudentController::class, 'login'])->name('student.login.submit');
});

Route::get('/dashboard',function(){
    $passes = TemporaryPass::latest()->take(30)->get();
    return view('dashboard',['passes'=>$passes]);
})->name('dashboard');

Route::post('/student/logout', [StudentController::class, 'logout'])
    ->middleware('auth:university')
    ->name('student.logout');

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

Route::get('/guest/applications/{application}', function (TemporaryPass $application) {
    return view('guest.application-show', ['application' => $application]);
})->name('guest.application.show');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Authentication
Route::middleware('guest:web')->group(function () {
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
});

Route::post('/admin/logout', [AdminController::class, 'logout'])
    ->middleware('auth:web')
    ->name('admin.logout');

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth:web')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/applications/manage', [AdminController::class, 'applicationsManage'])->name('applications.manage');
        Route::get('/applications/review/{application}', [AdminController::class, 'applicationsReview'])->name('applications.review');
        Route::get('/passes/expired', [AdminController::class, 'passesExpired'])->name('passes.expired');
        Route::view('/reports/lost-id', 'admin.reports.lost-id')->name('reports.lost.id');
    });

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', fn () => redirect()->route('login.choice'))->name('logout');

Route::get('/passes/{temporaryPass}/qr-code', [TemporaryPassController::class, 'qrCodeImage'])->name('passes.qr.image');
Route::get('/passes/verify/{token}', [TemporaryPassController::class, 'verifyByToken'])->name('passes.qr.verify');
Route::resource('passes', TemporaryPassController::class);

/*
|--------------------------------------------------------------------------
| Security Guard Portal
|--------------------------------------------------------------------------
*/

Route::prefix('security')->name('security.')->group(function () {
    Route::middleware('guest:security')->group(function () {
        Route::get('/login', [SecurityAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [SecurityAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth:security')->group(function () {
        Route::post('/logout', [SecurityAuthController::class, 'logout'])->name('logout');
        Route::get('/verify', [SecurityVerificationController::class, 'showPortal'])->name('verify');
        Route::post('/lookup', [SecurityVerificationController::class, 'lookup'])->name('lookup');
    });
});
