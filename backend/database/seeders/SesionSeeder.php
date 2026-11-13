<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Sesion;
use Illuminate\Database\Seeder;

class SesionSeeder extends Seeder
{
    public function run(): void
    {
        // [email del cliente, fecha, lugar, estado, servicios contratados]
        $sesiones = [
            ['user1@cifpaviles.es', '2026-06-20 11:00', 'Iglesia de Sabugo, Avilés', 'realizada', ['Boda completa', 'Álbum fotográfico']],
            ['user1@cifpaviles.es', '2026-11-06 16:00', 'Playa de Rodiles', 'realizada', ['Sesión postboda']],
            ['user2@cifpaviles.es', '2026-12-12 10:00', 'Parque Ferrera, Avilés', 'aceptada', ['Sesión de familia']],
            ['user2@cifpaviles.es', '2027-05-15 12:00', 'Basílica de Covadonga', 'pendiente', ['Comunión', 'Álbum fotográfico']],
            ['marta.alonso@example.com', '2027-04-10 17:00', 'Playa de Salinas', 'aceptada', ['Sesión preboda']],
            ['marta.alonso@example.com', '2027-07-03 12:30', 'Palacio de Valdés-Bazán', 'aceptada', ['Boda completa', 'Segundo fotógrafo', 'Álbum fotográfico']],
            ['javier.lopez@example.com', '2026-10-02 18:00', 'Domicilio familiar, Gijón', 'realizada', ['Sesión de embarazo']],
            ['javier.lopez@example.com', '2026-12-01 10:00', 'Domicilio familiar, Gijón', 'aceptada', ['Sesión newborn']],
            ['ana.gonzalez@example.com', '2027-09-18 13:00', 'Catedral de Oviedo', 'pendiente', ['Boda media jornada']],
            ['david.perez@example.com', '2026-09-12 12:00', 'Iglesia de Luanco', 'cancelada', ['Boda media jornada']],
            ['laura.sanchez@example.com', '2027-02-14 17:30', 'Cabo Peñas', 'rechazada', ['Sesión preboda']],
            ['carlos.garcia@example.com', '2027-06-05 11:00', 'Jardín Botánico, Gijón', 'pendiente', ['Sesión de familia', 'Álbum fotográfico']],
        ];

        foreach ($sesiones as [$email, $fecha, $lugar, $estado, $nombresServicios]) {
            $cliente = Cliente::whereHas('user', fn ($q) => $q->where('email', $email))->firstOrFail();

            $sesion = Sesion::updateOrCreate(
                ['cliente_id' => $cliente->id, 'fecha_solicitada' => $fecha],
                [
                    'lugar' => $lugar,
                    'estado' => $estado,
                    'fecha_confirmada' => in_array($estado, ['aceptada', 'realizada']) ? $fecha : null,
                ]
            );

            // Se copian precio y duración del catálogo como condiciones pactadas
            $lineas = [];
            foreach (Servicio::whereIn('nombre', $nombresServicios)->get() as $servicio) {
                $lineas[$servicio->id] = [
                    'precio_pactado' => $servicio->precio_base,
                    'duracion_minutos' => $servicio->duracion_minutos,
                    'extras_incluidos' => $servicio->nombre === 'Boda completa' ? 'Galería online y 50 fotos impresas' : null,
                ];
            }
            $sesion->servicios()->sync($lineas);
        }
    }
}
