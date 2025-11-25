<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailLogRequest;
use App\Http\Requests\UpdateEmailLogRequest;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(Auth::guard('web')->check(), 403);

        $status = strtolower((string) $request->query('status', ''));

        $query = EmailLog::with('temporaryPass')
            ->orderByDesc('sent_at');

        if (in_array($status, ['sent', 'failed', 'queued'])) {
            $query->where('status', $status);
        }

        $logs = $query->paginate(25);

        return view('admin.email-logs', [
            'logs' => $logs,
            'status' => $status,
        ]);
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
    public function store(StoreEmailLogRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailLog $emailLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailLog $emailLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmailLogRequest $request, EmailLog $emailLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailLog $emailLog)
    {
        //
    }
}
