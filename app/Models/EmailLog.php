<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_logs';

    /**
     * Indicates if the model should be timestamped.
     * We have a custom `sent_at` column instead.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'temporary_pass_id',
        'recipient_email',
        'subject',
        'status',
        'error_message',
        'sent_at',
    ];

    /**
     * Get the temporary pass that the email log belongs to.
     */
    public function temporaryPass()
    {
        return $this->belongsTo(TemporaryPass::class);
    }
}

