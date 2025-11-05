<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemporaryPassController; // <--- MUST INCLUDE THIS LINE

Route::get('/', function () {
    return view('welcome');
});


// --- YOUR TASK: Application (TemporaryPass) Routes ---
// Creates index, show, store, update, destroy routes for /applications
// This middleware ensures only logged-in users (Admin or Student/Guest) can access these routes.
Route::middleware('auth:web,university')->group(function () {
    Route::resource('applications', TemporaryPassController::class);
});


// Wendy's UserController login routes would go here