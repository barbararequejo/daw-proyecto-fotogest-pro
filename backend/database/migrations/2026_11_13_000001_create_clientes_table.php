<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            // Relación 1:1 con users. Si se borra la cuenta, se borra su ficha de cliente.
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nombre', 80);
            $table->string('apellidos', 120);
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
