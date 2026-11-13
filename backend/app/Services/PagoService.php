<?php

namespace App\Services;

use App\Models\Pago;
use App\Models\Sesion;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PagoService
{
    public function __construct(private SesionService $sesionService)
    {
    }

    /** Solo admin: todos los pagos, con filtro opcional por estado. */
    public function listar(?string $estado = null): Collection
    {
        return Pago::with('sesion.cliente')
            ->when($estado, fn ($q) => $q->where('estado', $estado))
            ->orderByDesc('fecha_pago')
            ->get();
    }

    /** Pagos de una sesión: el cliente solo puede consultar los de sus propias sesiones. */
    public function listarDeSesion(int $sesionId, User $user): Collection
    {
        $sesion = Sesion::findOrFail($sesionId);
        $this->sesionService->comprobarPropietario($sesion, $user);

        return $sesion->pagos()->orderBy('fecha_pago')->get();
    }

    public function crear(int $sesionId, array $datos): Pago
    {
        $sesion = Sesion::findOrFail($sesionId);

        return $sesion->pagos()->create($datos);
    }

    public function actualizar(int $id, array $datos): Pago
    {
        $pago = Pago::findOrFail($id);
        $pago->update($datos);

        return $pago;
    }
}
