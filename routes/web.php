<?php

use Illuminate\Support\Facades\Route;

// Method, route name, Callback/Controller (basically a function), Action
// get/post

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin-login', function(){
    return view('adminPages.login');
});

Route::get('admin-dashboard', function(){
    return view('adminPages.dashboard');
});

Route::get('manage-users', function(){
    return view('adminPages.userManagement');
});