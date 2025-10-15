<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    // Human-readable role
    public function getRoleNameAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->role));
    }
}
