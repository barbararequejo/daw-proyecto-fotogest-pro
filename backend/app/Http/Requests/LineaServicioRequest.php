<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LineaServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'precio_pactado' => ['sometimes', 'numeric', 'min:0', 'max:999999.99'],
            'duracion_minutos' => ['sometimes', 'integer', 'min:1', 'max:1440'],
            'extras_incluidos' => ['nullable', 'string', 'max:255'],
        ];
    }
}
