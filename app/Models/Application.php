<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Application extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_id';
    protected $guarded = [];

    /**
     * Get the owning applicant (Student or Guest) of the application (Polymorphic Inverse).
     */
    public function applicant(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the administrator who processed the application.
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(Administrator::class, 'processed_by_admin', 'admin_id');
    }

    /**
     * Get the pass associated with the application.
     */
    public function pass(): HasOne
    {
        return $this->hasOne(Pass::class, 'application_id', 'application_id');
    }
}
