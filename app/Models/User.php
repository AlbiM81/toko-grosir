<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // ← TAMBAHKAN INI

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // ← TAMBAHKAN HasRoles

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // kolom lama tetap ada untuk backward compat
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // Helper methods (tetap ada untuk kemudahan)
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isKaryawan(): bool
    {
        return $this->hasRole('karyawan');
    }

    public function isPembeli(): bool
    {
        return $this->hasRole('pembeli');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}