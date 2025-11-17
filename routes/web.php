<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\SecurityAuthController;
use App\Http\Controllers\SecurityVerificationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TemporaryPassController;
use App\Models\TemporaryPass;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login.choice'));

Route::view('/login/choice', 'auth.login-choice')->name('login.choice');
Route::get('/login', fn () => redirect()->route('login.choice'))->name('login');

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest:university')->group(function () {
    Route::get('/login/student', [StudentController::class, 'showLoginForm'])->name('student.login');
    Route::post('/login/student', [StudentController::class, 'login'])->name('student.login.submit');
});

Route::middleware('auth:university')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');

    Route::get('/applications/create', [StudentController::class, 'showTemporaryPassForm'])->name('application.create');
    Route::post('/applications', [StudentController::class, 'applyTemporaryPass'])->name('application.store');

    Route::get('/applications/{application}', function (TemporaryPass $application) {
        return view('application.show', ['application' => $application]);
    })->name('application.show');

    Route::get('/report/lost-id', fn () => view('report.lost-id'))->name('report.lost.id');
    Route::post('/report/lost-id', [StudentController::class, 'storeLostId'])->name('report.lost.id.store');

    Route::post('/student/logout', [StudentController::class, 'logout'])->name('student.logout');
});

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest:guest')->group(function () {
    Route::get('/login/guest', [GuestController::class, 'showLogin'])->name('guest.login');
    Route::post('/login/guest', [GuestController::class, 'login'])->name('guest.login.submit');
});

Route::middleware('auth:guest')->group(function () {
    Route::get('/guest/dashboard', [GuestController::class, 'dashboard'])->name('guest.dashboard');
    Route::post('/guest/logout', [GuestController::class, 'logout'])->name('guest.logout');
    Route::get('/guest/profile', [GuestController::class, 'profile'])->name('guest.profile');
    Route::get('/guest/applications/create', [GuestController::class, 'createApplication'])->name('guest.application.create');
    Route::post('/guest/applications', [GuestController::class, 'storeApplication'])->name('guest.application.store');
    Route::get('/guest/applications/{application}', [GuestController::class, 'showApplication'])->name('guest.application.show');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

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
| Shared Routes
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
