<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryPass extends Model
{
    /** @use HasFactory<\Database\Factories\TemporaryPassFactory> */
    use HasFactory;

    public const MEMBER_REASON_LABELS = [
        'lost_id' => 'Lost University ID',
        'misplaced_id' => 'Misplaced University ID',
        'damaged_card' => 'Damaged ID Card',
        'campus_event' => 'Attending Campus Event',
        'other' => 'Other',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'passable_id',
        'passable_type',
        'status',
        'reason',
        'qr_code_token',
        'qr_code_path',
        'valid_from',
        'valid_until',
        'approved_by',
    ];

    /**
     * Additional appended attributes.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'reason_label',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [

        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    /**
     * Get the parent passable model (UniversityMember or Guest).
     */
    public function passable()
    {
        return $this->morphTo();
    }

    /**
     * Get the admin who approved/rejected the pass.
     */
    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Get the email logs for the temporary pass.
     */
    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }

    /**
     * Convenience helper: return human-readable reason label.
     */
    public function getReasonLabelAttribute(): string
    {
        $labels = static::reasonLabels();

        return $labels[$this->reason] ?? $this->reason;
    }

    /**
     * Log an email event associated with this pass.
     */
    public function logEmail(string $recipient, string $subject, string $status = 'queued', ?string $errorMessage = null): EmailLog
    {
        return $this->emailLogs()->create([
            'recipient_email' => $recipient,
            'subject' => $subject,
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => now(),
        ]);
    }

    /**
     * All reason labels recognised by the system.
     *
     * @return array<string, string>
     */
    public static function reasonLabels(): array
    {
        return self::MEMBER_REASON_LABELS;
    }
}
