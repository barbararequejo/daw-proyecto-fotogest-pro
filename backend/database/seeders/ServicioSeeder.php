<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            ['Boda completa', 'Cobertura desde los preparativos hasta el primer baile.', 1450.00, 600, true],
            ['Boda media jornada', 'Ceremonia, reportaje de pareja y banquete.', 950.00, 300, true],
            ['Sesión preboda', 'Sesión de pareja en exterior antes de la boda.', 220.00, 90, true],
            ['Sesión postboda', 'Sesión de pareja con el vestido después de la boda.', 280.00, 120, true],
            ['Comunión', 'Reportaje de la ceremonia y sesión en exterior.', 350.00, 180, true],
            ['Sesión de embarazo', 'Sesión en exterior o en casa durante el embarazo.', 150.00, 60, true],
            ['Sesión newborn', 'Sesión de recién nacido en casa de la familia.', 180.00, 120, true],
            ['Sesión de familia', 'Sesión en exterior para toda la familia.', 140.00, 60, true],
            ['Álbum fotográfico', 'Diseño e impresión de álbum de 30 páginas.', 320.00, 30, true],
            ['Segundo fotógrafo', 'Apoyo de un segundo fotógrafo durante la boda.', 300.00, 480, false],
        ];

        foreach ($servicios as [$nombre, $descripcion, $precio, $duracion, $disponible]) {
            Servicio::updateOrCreate(
                ['nombre' => $nombre],
                [
                    'descripcion' => $descripcion,
                    'precio_base' => $precio,
                    'duracion_minutos' => $duracion,
                    'disponible' => $disponible,
                ]
            );
        }
    }
}
