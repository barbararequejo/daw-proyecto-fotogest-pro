<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SesionEstadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estado' => ['required', 'in:pendiente,aceptada,rechazada,realizada,cancelada'],
            'fecha_confirmada' => ['nullable', 'date', 'required_if:estado,aceptada'],
            'lugar' => ['nullable', 'string', 'max:150'],
        ];
    }
}
