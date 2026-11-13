<?php

namespace App\Services;

use App\Models\Fotografia;
use App\Models\Sesion;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Las fotos se guardan en el disco privado de Laravel (storage/app/private), que no es accesible por URL.
 * Solo se pueden descargar a través de la API, después de comprobar el permiso.
 * En el Hito 3 este disco se cambiará por Cloudinary.
 */
class FotografiaService
{
    public function __construct(private SesionService $sesionService)
    {
    }

    public function listarDeSesion(int $sesionId, User $user): Collection
    {
        $sesion = Sesion::findOrFail($sesionId);
        $this->sesionService->comprobarPropietario($sesion, $user);

        return $sesion->fotografias()->orderBy('nombre_archivo')->get();
    }

    public function subir(int $sesionId, UploadedFile $archivo, ?string $descripcion): Fotografia
    {
        $sesion = Sesion::findOrFail($sesionId);

        $nombreGuardado = Str::uuid().'.'.$archivo->extension();
        $ruta = $archivo->storeAs("galerias/sesion_{$sesion->id}", $nombreGuardado, 'local');

        return $sesion->fotografias()->create([
            'nombre_archivo' => $archivo->getClientOriginalName(),
            'ruta_almacenamiento' => $ruta,
            'tamano_kb' => (int) ceil($archivo->getSize() / 1024),
            'descripcion' => $descripcion,
        ]);
    }

    public function descargar(int $id, User $user): StreamedResponse
    {
        $foto = Fotografia::with('sesion')->findOrFail($id);
        $this->sesionService->comprobarPropietario($foto->sesion, $user);

        if (! Storage::disk('local')->exists($foto->ruta_almacenamiento)) {
            abort(404, 'El archivo de la fotografía no está disponible.');
        }

        return Storage::disk('local')->download($foto->ruta_almacenamiento, $foto->nombre_archivo);
    }

    public function eliminar(int $id): void
    {
        $foto = Fotografia::findOrFail($id);
        Storage::disk('local')->delete($foto->ruta_almacenamiento);
        $foto->delete();
    }
}
