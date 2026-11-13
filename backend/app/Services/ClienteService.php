<?php

namespace App\Services;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Collection;

class ClienteService
{
    public function listar(): Collection
    {
        return Cliente::with('user')->withCount('sesiones')->orderBy('apellidos')->get();
    }

    public function obtener(int $id): Cliente
    {
        return Cliente::with(['user', 'sesiones.servicios'])->findOrFail($id);
    }
}
