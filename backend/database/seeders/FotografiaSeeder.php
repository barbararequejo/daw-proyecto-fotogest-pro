<?php

namespace Database\Seeders;

use App\Models\Fotografia;
use App\Models\Sesion;
use Illuminate\Database\Seeder;

/**
 * Registra los datos de las fotos de las sesiones ya realizadas.
 * Los archivos de ejemplo no existen físicamente; la subida real se prueba con el endpoint POST.
 */
class FotografiaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Sesion::where('estado', 'realizada')->get() as $sesion) {
            for ($i = 1; $i <= 5; $i++) {
                $nombre = sprintf('sesion%02d_foto%02d.jpg', $sesion->id, $i);

                Fotografia::updateOrCreate(
                    ['ruta_almacenamiento' => "galerias/sesion_{$sesion->id}/{$nombre}"],
                    [
                        'sesion_id' => $sesion->id,
                        'nombre_archivo' => $nombre,
                        'tamano_kb' => 2400 + $i * 150,
                        'descripcion' => "Fotografía {$i} de la sesión",
                    ]
                );
            }
        }
    }
}
