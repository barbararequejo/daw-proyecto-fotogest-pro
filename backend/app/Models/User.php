<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = ['email', 'password', 'rol'];

    // El hash de la contraseña nunca sale en las respuestas JSON.
    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Laravel aplica bcrypt al asignar la contraseña
        ];
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class);
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    // Métodos que pide la librería JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return ['rol' => $this->rol];
    }
}
