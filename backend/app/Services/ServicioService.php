<?php

namespace App\Services;

use App\Exceptions\ConflictoException;
use App\Models\Servicio;
use Illuminate\Database\Eloquent\Collection;

class ServicioService
{
    /** Catálogo público: solo servicios disponibles. El admin ve también los desactivados. */
    public function listar(bool $incluirNoDisponibles = false): Collection
    {
        return Servicio::query()
            ->when(! $incluirNoDisponibles, fn ($q) => $q->where('disponible', true))
            ->orderBy('nombre')
            ->get();
    }

    public function obtener(int $id, bool $incluirNoDisponibles = false): Servicio
    {
        return Servicio::query()
            ->when(! $incluirNoDisponibles, fn ($q) => $q->where('disponible', true))
            ->findOrFail($id); // si no existe -> 404
    }

    public function crear(array $datos): Servicio
    {
        return Servicio::create($datos);
    }

    public function actualizar(int $id, array $datos): Servicio
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->update($datos);

        return $servicio;
    }

    /** Un servicio que ya forma parte de alguna sesión no se borra (RESTRICT): se debe desactivar. */
    public function eliminar(int $id): void
    {
        $servicio = Servicio::findOrFail($id);

        if ($servicio->sesiones()->exists()) {
            throw new ConflictoException('El servicio está incluido en sesiones. Desactívalo en lugar de borrarlo.');
        }

        $servicio->delete();
    }
}
