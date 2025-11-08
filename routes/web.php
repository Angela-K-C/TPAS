<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
