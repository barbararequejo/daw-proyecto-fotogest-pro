<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Servicio extends Model
{
    protected $table = 'servicios_fotograficos';

    protected $fillable = ['nombre', 'descripcion', 'precio_base', 'duracion_minutos', 'disponible'];

    protected function casts(): array
    {
        return [
            'precio_base' => 'decimal:2',
            'disponible' => 'boolean',
        ];
    }

    public function sesiones(): BelongsToMany
    {
        return $this->belongsToMany(Sesion::class, 'sesion_servicio', 'servicio_id', 'sesion_id')
            ->withPivot(['precio_pactado', 'duracion_minutos', 'extras_incluidos'])
            ->withTimestamps();
    }
}
