<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsuarioClienteSeeder::class,
            ServicioSeeder::class,
            SesionSeeder::class,
            PagoSeeder::class,
            FotografiaSeeder::class,
        ]);
    }
}
