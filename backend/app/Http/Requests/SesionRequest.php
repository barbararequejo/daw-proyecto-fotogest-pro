<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SesionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['sometimes', 'integer'], // solo lo usa el admin
            'fecha_solicitada' => ['required', 'date', 'after:today'],
            'lugar' => ['nullable', 'string', 'max:150'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
            'servicios' => ['required', 'array', 'min:1'],
            'servicios.*' => ['integer', 'distinct'],
        ];
    }
}
