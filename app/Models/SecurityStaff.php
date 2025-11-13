<?php

/**
 * Security bundle copy:
 * Simple Authenticatable model backing the `security` guard.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SecurityStaff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'security_staff';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
