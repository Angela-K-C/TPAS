<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Student extends Model
{
    use HasFactory;
    
    // Disable Laravel's default auto-incrementing ID since the schema listed 
    // VARCHAR fields, but we'll stick to convention for simplicity.
    protected $guarded = [];

    /**
     * Get all of the student's applications (Polymorphic One-to-Many).
     */
    public function applications(): MorphMany
    {
        return $this->morphMany(Application::class, 'applicant');
    }
}
