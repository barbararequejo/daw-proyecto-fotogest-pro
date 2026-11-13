<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
            'nombre' => ['required', 'string', 'max:80'],
            'apellidos' => ['required', 'string', 'max:120'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+ ]+$/'],
        ];
    }
}
