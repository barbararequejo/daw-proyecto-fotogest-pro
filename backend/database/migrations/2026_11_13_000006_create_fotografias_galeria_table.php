<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fotografias_galeria', function (Blueprint $table) {
            $table->id();
            // CASCADE: las fotos pertenecen a la sesión; si se borra la sesión, se borran sus registros.
            $table->foreignId('sesion_id')->constrained('sesiones_citas')->cascadeOnDelete();
            $table->string('nombre_archivo', 150);
            $table->string('ruta_almacenamiento', 255)->unique();
            $table->unsignedInteger('tamano_kb')->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->timestamps(); // created_at = fecha de subida
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fotografias_galeria');
    }
};
