<?php

namespace Database\Seeders;

use App\Models\Pago;
use App\Models\Sesion;
use Illuminate\Database\Seeder;

class PagoSeeder extends Seeder
{
    public function run(): void
    {
        // Las sesiones aceptadas tienen una señal; las realizadas, señal y pago final.
        foreach (Sesion::with('servicios')->whereIn('estado', ['aceptada', 'realizada'])->get() as $sesion) {
            $total = $sesion->servicios->sum(fn ($s) => $s->pivot->precio_pactado);
            $senal = round($total * 0.3, 2);
            $fechaSenal = $sesion->fecha_solicitada->copy()->subMonths(2)->toDateString();

            Pago::updateOrCreate(
                ['sesion_id' => $sesion->id, 'concepto' => 'Señal de reserva (30 %)'],
                ['importe' => $senal, 'fecha_pago' => $fechaSenal, 'estado' => 'pagado', 'metodo_pago' => 'transferencia']
            );

            Pago::updateOrCreate(
                ['sesion_id' => $sesion->id, 'concepto' => 'Pago final'],
                [
                    'importe' => $total - $senal,
                    'fecha_pago' => $sesion->fecha_solicitada->toDateString(),
                    'estado' => $sesion->estado === 'realizada' ? 'pagado' : 'pendiente',
                    'metodo_pago' => 'bizum',
                ]
            );
        }
    }
}
