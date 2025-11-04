<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Guest extends Model
{
    use HasFactory;
    
    // Since 'guest_id' is PK and not the default 'id'
    protected $primaryKey = 'guest_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * Get all of the guest's applications (Polymorphic One-to-Many).
     */
    public function applications(): MorphMany
    {
        return $this->morphMany(Application::class, 'applicant');
    }
}
