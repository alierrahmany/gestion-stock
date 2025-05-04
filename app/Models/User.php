<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'image',
    ];

    const ROLES = ['admin', 'gestionnaire', 'magasin'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if the user has admin privileges.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin'; // Adjust 'role' and 'admin' based on your database structure
    }

    public function isGestionnaire()
    {
        return $this->role === 'gestionnaire';
    }

    public function isMagasin()
    {
        return $this->role === 'magasin';
    }
}
