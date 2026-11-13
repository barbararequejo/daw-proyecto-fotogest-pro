<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla intermedia de la relación M:N entre sesiones y servicios.
 * Guarda las condiciones pactadas para cada servicio dentro de una sesión.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesion_servicio', function (Blueprint $table) {
            // CASCADE: la línea no tiene sentido sin su sesión.
            $table->foreignId('sesion_id')->constrained('sesiones_citas')->cascadeOnDelete();
            // RESTRICT: un servicio ya contratado no se borra; se desactiva.
            $table->foreignId('servicio_id')->constrained('servicios_fotograficos')->restrictOnDelete();
            $table->decimal('precio_pactado', 8, 2);
            $table->unsignedSmallInteger('duracion_minutos');
            $table->string('extras_incluidos', 255)->nullable();
            $table->timestamps();

            $table->primary(['sesion_id', 'servicio_id']);
        });

        if (DB::getDriverName() === 'mysql' || DB::getDriverName() === 'mariadb') {
            DB::statement('ALTER TABLE sesion_servicio ADD CONSTRAINT chk_ss_precio CHECK (precio_pactado >= 0)');
            DB::statement('ALTER TABLE sesion_servicio ADD CONSTRAINT chk_ss_duracion CHECK (duracion_minutos > 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sesion_servicio');
    }
};
