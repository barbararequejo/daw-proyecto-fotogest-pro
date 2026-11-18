<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Cuentas de prueba: la administradora usa "Admin1234!" y los clientes "User1234!".
 * updateOrCreate hace que el seeder se pueda ejecutar varias veces sin duplicar datos.
 */
class UsuarioClienteSeeder extends Seeder
{
    public const PASSWORD_ADMIN = 'Admin1234!';

    public const PASSWORD_CLIENTE = 'User1234!';

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cifpaviles.es'],
            ['password' => self::PASSWORD_ADMIN, 'rol' => 'admin']
        );

        $clientes = [
            ['user1@cifpaviles.es', 'Lucía', 'Fernández García', '612345678'],
            ['user2@cifpaviles.es', 'Pablo', 'Menéndez Suárez', '623456789'],
            ['marta.alonso@example.com', 'Marta', 'Alonso Rodríguez', '634567890'],
            ['javier.lopez@example.com', 'Javier', 'López Martínez', '645678901'],
            ['ana.gonzalez@example.com', 'Ana', 'González Fernández', '656789012'],
            ['david.perez@example.com', 'David', 'Pérez Álvarez', '667890123'],
            ['laura.sanchez@example.com', 'Laura', 'Sánchez Díaz', '678901234'],
            ['carlos.garcia@example.com', 'Carlos', 'García Fernández', '689012345'],
            ['sara.rodriguez@example.com', 'Sara', 'Rodríguez Menéndez', '690123456'],
            ['ivan.martinez@example.com', 'Iván', 'Martínez Iglesias', '601234567'],
        ];

        foreach ($clientes as [$email, $nombre, $apellidos, $telefono]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                ['password' => self::PASSWORD_CLIENTE, 'rol' => 'cliente']
            );

            Cliente::updateOrCreate(
                ['user_id' => $user->id],
                ['nombre' => $nombre, 'apellidos' => $apellidos, 'telefono' => $telefono]
            );
        }
    }
}
