<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The database connection that should be used by the model.
     * This points to the connection we defined in config/database.php
     *
     * @var string
     */
    protected $connection = 'university';

    /**
     * The table associated with the model.
     * 
     *
     * @var string
     */
    protected $table = 'students';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo_url',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get all of the member's temporary passes.
     */
    public function passes()
    {
        return $this->morphMany(TemporaryPass::class, 'passable');
    }
}

