<?php

namespace App\Http\Controllers;

use App\Models\TemporaryPass;
use App\Models\Student;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTemporaryPassRequest;
use App\Http\Requests\UpdateTemporaryPassRequest;

class TemporaryPassController extends Controller
{
    /**
     * Display a listing of the resource (READ).
     * Admins see all passes. Students/Guests only see their own.
     */
    public function index()
    {
        // Check Admin Guard (web)
        if (Auth::guard('web')->check()) {
            return TemporaryPass::with('passable')->latest()->get();
        }

        // Check Student/Guest Guard (university)
        if (Auth::guard('university')->check()) {
            $user = Auth::guard('university')->user();
            return TemporaryPass::where('passable_type', $user->getMorphClass()) 
                                ->where('passable_id', $user->id)
                                ->latest()
                                ->get();
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    /**
     * Store a newly created resource in storage (CREATE).
     */
    public function store(Request $request) 
    {
        if (!Auth::guard('university')->check()) {
             return response()->json(['error' => 'Only students or guests can apply.'], 403);
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $pass = new TemporaryPass();
        $pass->reason = $request->input('reason');
        $pass->status = 'pending'; 
        
        $pass->passable()->associate(Auth::guard('university')->user());
        $pass->save();

        return response()->json($pass, 201);
    }

    /**
     * Display the specified resource (READ one).
     */
    public function show(TemporaryPass $temporaryPass)
    {
        // Admin: allowed to see any
        if (Auth::guard('web')->check()) {
            return $temporaryPass->load('passable');
        }

        // Student/Guest: allowed to see only their own
        if (Auth::guard('university')->check()) {
            $user = Auth::guard('university')->user();
            if ($temporaryPass->passable_type === $user->getMorphClass() && $temporaryPass->passable_id === $user->id) {
                return $temporaryPass->load('passable');
            }
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    /**
     * Update the specified resource in storage (UPDATE).
     */
    public function update(Request $request, TemporaryPass $temporaryPass)
    {
        // Only Admins can update passes (Approve/Reject)
        if (!Auth::guard('web')->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'sometimes|in:pending,approved,rejected',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $temporaryPass->update($request->only(['status', 'valid_from', 'valid_until']));

        if ($request->input('status') === 'approved') {
            $temporaryPass->approved_by = Auth::guard('web')->id();
        }
        
        $temporaryPass->save();

        return response()->json($temporaryPass);
    }

    /**
     * Remove the specified resource from storage (DELETE).
     */
    public function destroy(TemporaryPass $temporaryPass)
    {
        // Only Admins can delete passes
        if (!Auth::guard('web')->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $temporaryPass->delete();

        return response()->noContent(); 
    }
}