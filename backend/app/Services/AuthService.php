<?php

namespace App\Services;

use App\Exceptions\ConflictoException;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;

class AuthService
{
    /**
     * Registra una cuenta de cliente: crea el usuario y su ficha de cliente en una transacción.
     * Si falla cualquiera de los dos INSERT, no se guarda nada (rollback).
     */
    public function registrar(array $datos): array
    {
        if (User::where('email', $datos['email'])->exists()) {
            throw new ConflictoException('Ya existe una cuenta con ese email.');
        }

        $user = DB::transaction(function () use ($datos) {
            $user = User::create([
                'email' => $datos['email'],
                'password' => $datos['password'], // el cast 'hashed' del modelo aplica bcrypt
                'rol' => 'cliente',
            ]);

            Cliente::create([
                'user_id' => $user->id,
                'nombre' => $datos['nombre'],
                'apellidos' => $datos['apellidos'],
                'telefono' => $datos['telefono'] ?? null,
            ]);

            return $user;
        });

        return $this->respuestaToken(auth('api')->login($user), $user->load('cliente'));
    }

    public function login(string $email, string $password): array
    {
        // attempt() compara la contraseña con el hash bcrypt guardado
        $token = auth('api')->attempt(['email' => $email, 'password' => $password]);

        if (! $token) {
            throw new AuthenticationException('Email o contraseña incorrectos.');
        }

        return $this->respuestaToken($token, auth('api')->user()->load('cliente'));
    }

    public function logout(): void
    {
        auth('api')->logout(); // invalida el token actual
    }

    private function respuestaToken(string $token, User $user): array
    {
        return [
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, // segundos
            'user' => $user,
        ];
    }
}
