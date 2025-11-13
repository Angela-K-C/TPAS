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

Route::get('/guest/applications/{application}', function (TemporaryPass $application) {
    return view('guest.application-show', ['application' => $application]);
})->name('guest.application.show');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Authentication
Route::view('/admin/login', 'admin.login')->name('admin.login');

// Dashboard
Route::get('/admin/dashboard', function () {
    $passes = TemporaryPass::latest()->take(5)->get();
    return view('admin.dashboard', ['passes' => $passes]);
})->name('admin.dashboard');


// Applications
Route::get('/admin/applications/manage', function (Request $request) {
    $status = $request->query('status', 'Pending');

    $query = TemporaryPass::query();

    if ($status !== 'All') {
        $query->where('status', $status);
    }

    $applications = $query->latest()->get();

    return view('admin.applications.manage', [
        'applications' => $applications,
        'currentFilter' => $status, 
    ]);
})->name('admin.applications.manage');


Route::view('/admin/applications/show', 'admin.applications.show')->name('admin.applications.show');

Route::get('/admin/applications/review/{application}', function ($id) {
    $application = TemporaryPass::findOrFail($id);
    return view('admin.application-review', ['application' => $application]);
})->name('admin.applications.review');

Route::post('/admin/applications/{application}/approve', function ($id) {
    $application = TemporaryPass::findOrFail($id);
    $application->status = 'approved';
    $application->save();
    return redirect()->route('admin.dashboard');
})->name('admin.applications.approve');

Route::post('/admin/applications/{application}/reject', function ($id) {
    $application = TemporaryPass::findOrFail($id);
    $application->status = 'rejected';
    $application->save();
    return redirect()->route('admin.dashboard');
})->name('admin.applications.reject');

// Passes and Reports

Route::get('/admin/passes/expired', function () {
    $expiredPasses = TemporaryPass::where('valid_until', '<', Carbon::now())->get();

    return view('admin.passes.expired', ['expiredPasses' => $expiredPasses]);
})->name('admin.passes.expired');

Route::view('/admin/reports/lost-id', 'admin.reports.lost-id')->name('admin.reports.lost.id');

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', fn () => redirect()->route('login.choice'))->name('logout');

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
