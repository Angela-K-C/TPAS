<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Guest;

class GuestController extends Controller
{
    // Show login form [For guest - redirect to homepage]
    public function showLogin() {
        return view('test.home');
    }
}
