 <?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('test.home');
})->name('test.home');

Route::get('/login', function() {
    if (Auth::guard('web')->check() | Auth::guard('university')->check()) {
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

// Logout
Route::post('/logout/admin', [AdminController::class, 'logout'])->name('admin.logout');
Route::post('/logout/student', [StudentController::class, 'logout'])->name('student.logout');