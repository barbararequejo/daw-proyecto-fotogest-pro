<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios_fotograficos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion');
            $table->decimal('precio_base', 8, 2);
            $table->unsignedSmallInteger('duracion_minutos');
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });

        if (DB::getDriverName() === 'mysql' || DB::getDriverName() === 'mariadb') {
            DB::statement('ALTER TABLE servicios_fotograficos ADD CONSTRAINT chk_servicio_precio CHECK (precio_base >= 0)');
            DB::statement('ALTER TABLE servicios_fotograficos ADD CONSTRAINT chk_servicio_duracion CHECK (duracion_minutos > 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios_fotograficos');
    }
};
