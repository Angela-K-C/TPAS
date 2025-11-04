<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePassRequest;
use App\Http\Requests\UpdatePassRequest;
use App\Models\Pass;

class PassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePassRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pass $pass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pass $pass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePassRequest $request, Pass $pass)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pass $pass)
    {
        //
    }
}
