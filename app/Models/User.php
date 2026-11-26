<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'user_code',
        'name',
        'username',
        'password',
        'role',
        'phone',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at'     => 'datetime',
    ];

    /**
     * Memeriksa apakah pengguna memiliki hak akses Administrator
     */
    public function isAdmin(): bool
    {
        return $this->role === 'ADMINISTRATOR';
    }

    /**
     * Memeriksa apakah pengguna memiliki hak akses Operator
     */
    public function isOperator(): bool
    {
        return $this->role === 'OPERATOR';
    }

    /**
     * Memeriksa status keaktifan akun
     */
    public function isActive(): bool
    {
        return $this->status === 'AKTIF';
    }
}
