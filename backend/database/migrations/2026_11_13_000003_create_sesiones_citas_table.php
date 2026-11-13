<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesiones_citas', function (Blueprint $table) {
            $table->id();
            // RESTRICT: no se puede borrar un cliente que tiene sesiones (se pierde el historial).
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->dateTime('fecha_solicitada');
            $table->dateTime('fecha_confirmada')->nullable();
            $table->string('lugar', 150)->nullable();
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada', 'realizada', 'cancelada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['cliente_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesiones_citas');
    }
};
