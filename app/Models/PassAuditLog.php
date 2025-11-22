<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassAuditLog extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * We only use a single `created_at` column.
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
        'admin_id',
        'action',
        'changes',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * The pass this log entry belongs to.
     */
    public function pass()
    {
        return $this->belongsTo(TemporaryPass::class, 'temporary_pass_id');
    }

    /**
     * The admin who performed the action.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}

