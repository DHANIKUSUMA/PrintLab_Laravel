<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return['password' => 'hashed'];
    }

    protected $fillable = [
        'google_id',
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    // Relasi: Satu user bisa memiliki banyak pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_user', 'id_user');
    }
}