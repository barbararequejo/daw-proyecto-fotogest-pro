<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fotografia extends Model
{
    protected $table = 'fotografias_galeria';

    protected $fillable = ['sesion_id', 'nombre_archivo', 'ruta_almacenamiento', 'tamano_kb', 'descripcion'];

    // La ruta interna del archivo no se muestra al cliente; se descarga por un endpoint protegido.
    protected $hidden = ['ruta_almacenamiento'];

    public function sesion(): BelongsTo
    {
        return $this->belongsTo(Sesion::class, 'sesion_id');
    }
}
