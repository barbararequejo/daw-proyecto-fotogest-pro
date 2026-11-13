<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = ['sesion_id', 'importe', 'fecha_pago', 'estado', 'metodo_pago', 'concepto'];

    protected function casts(): array
    {
        return [
            'importe' => 'decimal:2',
            'fecha_pago' => 'date:Y-m-d',
        ];
    }

    public function sesion(): BelongsTo
    {
        return $this->belongsTo(Sesion::class, 'sesion_id');
    }
}
