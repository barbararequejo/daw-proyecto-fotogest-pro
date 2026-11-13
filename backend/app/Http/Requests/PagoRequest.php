<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $obligatorio = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'importe' => [$obligatorio, 'numeric', 'gt:0', 'max:999999.99'],
            'fecha_pago' => [$obligatorio, 'date'],
            'estado' => ['sometimes', 'in:pendiente,pagado,devuelto'],
            'metodo_pago' => [$obligatorio, 'in:efectivo,transferencia,bizum,tarjeta'],
            'concepto' => ['nullable', 'string', 'max:150'],
        ];
    }
}
