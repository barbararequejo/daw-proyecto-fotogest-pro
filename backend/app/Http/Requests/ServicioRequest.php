<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Sirve para crear (POST, todo obligatorio) y para actualizar (PUT/PATCH, campos opcionales). */
class ServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $obligatorio = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'nombre' => [$obligatorio, 'string', 'max:100', Rule::unique('servicios_fotograficos', 'nombre')->ignore($this->route('id'))],
            'descripcion' => [$obligatorio, 'string', 'max:2000'],
            'precio_base' => [$obligatorio, 'numeric', 'min:0', 'max:999999.99'],
            'duracion_minutos' => [$obligatorio, 'integer', 'min:1', 'max:1440'],
            'disponible' => ['sometimes', 'boolean'],
        ];
    }
}
