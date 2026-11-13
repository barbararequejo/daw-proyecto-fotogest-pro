<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FotografiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'foto' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:15360'], // 15 MB
            'descripcion' => ['nullable', 'string', 'max:255'],
        ];
    }
}
