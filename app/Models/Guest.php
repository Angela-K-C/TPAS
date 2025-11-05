<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'profile_image_path',
    ];

    /**
     * Get all of the guest's temporary passes.
     */
    public function passes()
    {
        return $this->morphMany(TemporaryPass::class, 'passable');
    }
}

