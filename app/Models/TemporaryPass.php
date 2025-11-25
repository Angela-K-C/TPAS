<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\PassAuditLog;

class TemporaryPass extends Model
{
    /** @use HasFactory<\Database\Factories\TemporaryPassFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (self $pass) {
            $pass->ensureQrCodeAssets();
        });
    }

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
        'pass_type',
        'visitor_name',
        'national_id',
        'email',
        'phone',
        'host_name',
        'host_department',
        'purpose',
        'details',
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
     * Get the audit log entries associated with this pass.
     */
    public function auditLogs()
    {
        return $this->hasMany(PassAuditLog::class, 'temporary_pass_id')
            ->latest('created_at');
    }

    /**
     * Convenience helper: return human-readable reason label.
     */
      public function getReasonLabelAttribute(): string
    {
        // If the pass has a free-text purpose, surface that first for better context.
        if (! empty($this->purpose)) {
            return $this->purpose;
        }

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

    /**
     * Retrieve the most recent pass for this passable that has not been rejected.
     */
    public static function existingNonRejectedFor(Model $passable): ?self
    {
        return self::where('passable_type', $passable->getMorphClass())
            ->where('passable_id', $passable->getKey())
            ->where('status', '!=', 'rejected')
            ->where(function ($query) {
                $query->whereNull('valid_until')
                      ->orWhere('valid_until', '>', now());
            })
            ->latest()
            ->first();
    }

    /**
     * Retrieve a pass by its full QR token or the shortened 8-character reference.
     */
    public static function queryByTokenOrReference(string $token)
    {
        $token = trim($token);
        $reference = strtoupper(substr($token, 0, 8));

        return self::with('passable')
            ->where(function ($query) use ($token, $reference) {
                $query->where('qr_code_token', $token)
                    ->orWhereRaw('UPPER(LEFT(qr_code_token, 8)) = ?', [$reference]);
            });
    }

    /**
     * Ensure the pass has a QR token and image stored on disk.
     */
    public function ensureQrCodeAssets(): void
    {
        if (blank($this->qr_code_token)) {
            $this->forceFill(['qr_code_token' => (string) Str::uuid()])->saveQuietly();
        }

        if (blank($this->qr_code_path) || ! Storage::disk('public')->exists($this->qr_code_path)) {
            $this->generateQrCodeImage();
        }
    }

    /**
     * Generate and persist a QR code PNG pointing to the verification endpoint.
     */
    public function generateQrCodeImage(): void
    {
        if (blank($this->qr_code_token)) {
            $this->forceFill(['qr_code_token' => (string) Str::uuid()])->saveQuietly();
        }

        $payload = route('passes.qr.verify', ['token' => $this->qr_code_token]);

        $image = QrCode::format('svg')
            ->size(600)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($payload);

        $path = "qr-codes/pass-{$this->id}.svg";
        Storage::disk('public')->put($path, $image);

        $this->forceFill(['qr_code_path' => $path])->saveQuietly();
    }

    /**
     * Public URL for the stored QR code asset.
     */
    public function getQrCodeUrlAttribute(): ?string
    {
        if (! $this->qr_code_path) {
            return null;
        }

        return Storage::disk('public')->url($this->qr_code_path);
    }
}
