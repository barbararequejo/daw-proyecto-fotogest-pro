<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sesion extends Model
{
    protected $table = 'sesiones_citas';

    protected $fillable = ['cliente_id', 'fecha_solicitada', 'fecha_confirmada', 'lugar', 'estado', 'observaciones'];

    protected function casts(): array
    {
        return [
            'fecha_solicitada' => 'datetime',
            'fecha_confirmada' => 'datetime',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación M:N con los atributos propios de la tabla intermedia
    public function servicios(): BelongsToMany
    {
        return $this->belongsToMany(Servicio::class, 'sesion_servicio', 'sesion_id', 'servicio_id')
            ->withPivot(['precio_pactado', 'duracion_minutos', 'extras_incluidos'])
            ->withTimestamps();
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'sesion_id');
    }

    public function fotografias(): HasMany
    {
        return $this->hasMany(Fotografia::class, 'sesion_id');
    }
}
