<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            // RESTRICT: una sesión con pagos registrados no se puede borrar (registro económico).
            $table->foreignId('sesion_id')->constrained('sesiones_citas')->restrictOnDelete();
            $table->decimal('importe', 8, 2);
            $table->date('fecha_pago');
            $table->enum('estado', ['pendiente', 'pagado', 'devuelto'])->default('pendiente');
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'bizum', 'tarjeta']);
            $table->string('concepto', 150)->nullable();
            $table->timestamps();
        });

        if (DB::getDriverName() === 'mysql' || DB::getDriverName() === 'mariadb') {
            DB::statement('ALTER TABLE pagos ADD CONSTRAINT chk_pago_importe CHECK (importe > 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
