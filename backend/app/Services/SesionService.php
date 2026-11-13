<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Sesion;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SesionService
{
    /** El admin ve todas las sesiones; un cliente solo las suyas. */
    public function listar(User $user, ?string $estado = null): Collection
    {
        return Sesion::with(['cliente', 'servicios'])
            ->when(! $user->esAdmin(), fn ($q) => $q->where('cliente_id', $user->cliente?->id))
            ->when($estado, fn ($q) => $q->where('estado', $estado))
            ->orderByDesc('fecha_solicitada')
            ->get();
    }

    public function obtener(int $id, User $user): Sesion
    {
        $sesion = Sesion::with(['cliente', 'servicios'])->findOrFail($id);
        $this->comprobarPropietario($sesion, $user);

        return $sesion;
    }

    /**
     * Control de titularidad (anti-BOLA): un cliente solo accede a sus propias sesiones.
     * Si intenta acceder a una sesión de otro cliente, se responde 403.
     */
    public function comprobarPropietario(Sesion $sesion, User $user): void
    {
        if (! $user->esAdmin() && $sesion->cliente_id !== $user->cliente?->id) {
            throw new AccessDeniedHttpException('No tienes permiso para acceder a esta sesión.');
        }
    }

    /**
     * Crea la solicitud y sus líneas en sesion_servicio dentro de una transacción.
     * El precio y la duración se copian del catálogo para conservar las condiciones pactadas.
     */
    public function crear(User $user, array $datos): Sesion
    {
        $clienteId = $user->esAdmin() ? $datos['cliente_id'] ?? null : $user->cliente?->id;

        if (! $clienteId || ! Cliente::whereKey($clienteId)->exists()) {
            throw ValidationException::withMessages(['cliente_id' => 'Debes indicar un cliente válido.']);
        }

        $servicios = Servicio::whereIn('id', $datos['servicios'])->where('disponible', true)->get();

        if ($servicios->count() !== count(array_unique($datos['servicios']))) {
            throw ValidationException::withMessages(['servicios' => 'Alguno de los servicios no existe o no está disponible.']);
        }

        return DB::transaction(function () use ($clienteId, $datos, $servicios) {
            $sesion = Sesion::create([
                'cliente_id' => $clienteId,
                'fecha_solicitada' => $datos['fecha_solicitada'],
                'lugar' => $datos['lugar'] ?? null,
                'observaciones' => $datos['observaciones'] ?? null,
                'estado' => 'pendiente',
            ]);

            foreach ($servicios as $servicio) {
                $sesion->servicios()->attach($servicio->id, [
                    'precio_pactado' => $servicio->precio_base,
                    'duracion_minutos' => $servicio->duracion_minutos,
                ]);
            }

            return $sesion->load(['cliente', 'servicios']);
        });
    }

    /** Solo admin: cambia el estado y, si se acepta, la fecha confirmada. */
    public function actualizarEstado(int $id, array $datos): Sesion
    {
        $sesion = Sesion::findOrFail($id);
        $sesion->update($datos);

        return $sesion->load(['cliente', 'servicios']);
    }

    /** Solo admin: modifica las condiciones pactadas de un servicio dentro de la sesión. */
    public function actualizarLinea(int $sesionId, int $servicioId, array $datos): Sesion
    {
        $sesion = Sesion::findOrFail($sesionId);

        if (! $sesion->servicios()->whereKey($servicioId)->exists()) {
            abort(404, 'Ese servicio no forma parte de la sesión.');
        }

        $sesion->servicios()->updateExistingPivot($servicioId, $datos);

        return $sesion->load(['cliente', 'servicios']);
    }
}
