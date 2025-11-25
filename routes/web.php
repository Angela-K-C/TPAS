<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmailLogController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\SecurityAuthController;
use App\Http\Controllers\SecurityVerificationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TemporaryPassController;
use App\Models\TemporaryPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::prefix('guest')
    ->name('guest.')
    ->group(function () {
        Route::middleware('guest:guest')->group(function () {
            Route::get('/login', [GuestController::class, 'showLogin'])->name('login');
            Route::post('/login', [GuestController::class, 'login'])->name('login.submit');
        });

        Route::middleware('auth:guest')->group(function () {
            Route::get('/dashboard', [GuestController::class, 'dashboard'])->name('dashboard');
            Route::post('/logout', [GuestController::class, 'logout'])->name('logout');
            Route::get('/profile', [GuestController::class, 'profile'])->name('profile');
            Route::get('/applications/create', [GuestController::class, 'createApplication'])->name('application.create');
            Route::post('/applications', [GuestController::class, 'storeApplication'])->name('application.store');
            Route::get('/applications/{application}', [GuestController::class, 'showApplication'])->name('application.show');
        });
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
        Route::get('/reports/lost-id', [AdminController::class, 'reportsLostId'])->name('reports.lost.id');
        Route::get('/email-logs', [EmailLogController::class, 'index'])->name('email.logs');
        Route::post('/passes/{temporaryPass}/reset', [AdminController::class, 'resetPass'])->name('passes.reset');
        Route::post('/passes/reset-by-identifier', [AdminController::class, 'resetPassByIdentifier'])->name('passes.reset.identifier');
    });

/*
|--------------------------------------------------------------------------
| Shared Routes
|--------------------------------------------------------------------------
*/

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    Auth::guard('university')->logout();
    Auth::guard('guest')->logout();
    Auth::guard('security')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login.choice');
})->name('logout');

Route::get('/passes', [TemporaryPassController::class, 'index'])->name('passes.index');
Route::get('/passes/create', [TemporaryPassController::class, 'create'])->name('passes.create');
Route::post('/passes', [TemporaryPassController::class, 'store'])->name('passes.store');
Route::view('/help', 'help')->name('help');
Route::get('/passes/{temporaryPass}', [TemporaryPassController::class, 'show'])->name('passes.show');
Route::get('/passes/{temporaryPass}/qr-code', [TemporaryPassController::class, 'qrCodeImage'])->name('passes.qr.image');
Route::get('/passes/{temporaryPass}/qr-code.pdf', [TemporaryPassController::class, 'qrCodePdf'])->name('passes.qr.pdf');
Route::get('/passes/verify/{token}', [TemporaryPassController::class, 'verifyByToken'])->name('passes.qr.verify');
Route::put('/passes/{temporaryPass}', [TemporaryPassController::class, 'update'])
    ->middleware('auth:web')
    ->name('passes.update');
Route::delete('/passes/{temporaryPass}', [TemporaryPassController::class, 'destroy'])
    ->middleware('auth:web')
    ->name('passes.destroy');

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
