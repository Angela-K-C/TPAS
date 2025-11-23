<?php

namespace App\Support;

use App\Models\TemporaryPass;
use Illuminate\Support\Facades\Log;

class Observability
{
    /**
     * Record a pass approval event with metrics + structured log.
     */
    public static function recordApproval(TemporaryPass $pass, int $adminId): void
    {
        Log::channel('structured')->info('temporary_pass.approved', [
            'temporary_pass_id' => $pass->id,
            'admin_id' => $adminId,
            'passable_type' => $pass->passable_type,
            'passable_id' => $pass->passable_id,
            'status' => $pass->status,
        ]);
    }

    /**
     * Record a pass rejection event with metrics + structured log.
     */
    public static function recordRejection(TemporaryPass $pass, int $adminId): void
    {
        Log::channel('structured')->info('temporary_pass.rejected', [
            'temporary_pass_id' => $pass->id,
            'admin_id' => $adminId,
            'passable_type' => $pass->passable_type,
            'passable_id' => $pass->passable_id,
            'status' => $pass->status,
        ]);
    }

    /**
     * Record a verification attempt, latency, and failure rate.
     */
    public static function recordVerificationAttempt(?TemporaryPass $pass, string $token, bool $success, float $latencyMs): void
    {
        Log::channel('structured')->info($success ? 'verification.success' : 'verification.failed', [
            'token' => $token,
            'temporary_pass_id' => $pass?->id,
            'status' => $pass?->status,
            'latency_ms' => round($latencyMs, 2),
        ]);
    }

    /**
     * Record an email delivery attempt for the dashboard + metrics.
     */
    public static function recordEmailEvent(TemporaryPass $pass, string $recipient, string $status, ?string $errorMessage = null): void
    {
        Log::channel('structured')->info('email.delivery', [
            'temporary_pass_id' => $pass->id,
            'recipient' => $recipient,
            'status' => strtolower($status),
            'error' => $errorMessage,
        ]);
    }
}
