<?php

// Mensajes de validación en español para las reglas que usa la API
return [
    'after' => 'El campo :attribute debe ser una fecha posterior a :date.',
    'array' => 'El campo :attribute debe ser una lista.',
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'date' => 'El campo :attribute no es una fecha válida.',
    'distinct' => 'El campo :attribute tiene un valor duplicado.',
    'email' => 'El campo :attribute debe ser un email válido.',
    'file' => 'El campo :attribute debe ser un archivo.',
    'gt' => [
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
    ],
    'in' => 'El valor de :attribute no es válido.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'max' => [
        'file' => 'El archivo :attribute no puede superar los :max kB.',
        'numeric' => 'El campo :attribute no puede ser mayor que :max.',
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
    ],
    'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'array' => 'El campo :attribute debe tener al menos :min elemento(s).',
        'numeric' => 'El campo :attribute debe ser como mínimo :min.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'numeric' => 'El campo :attribute debe ser un número.',
    'regex' => 'El formato de :attribute no es válido.',
    'required' => 'El campo :attribute es obligatorio.',
    'required_if' => 'El campo :attribute es obligatorio cuando :other es :value.',
    'string' => 'El campo :attribute debe ser un texto.',
    'unique' => 'El valor de :attribute ya está en uso.',

    'attributes' => [
        'password' => 'contraseña',
        'telefono' => 'teléfono',
        'precio_base' => 'precio base',
        'precio_pactado' => 'precio pactado',
        'duracion_minutos' => 'duración en minutos',
        'fecha_solicitada' => 'fecha solicitada',
        'fecha_confirmada' => 'fecha confirmada',
        'fecha_pago' => 'fecha de pago',
        'metodo_pago' => 'método de pago',
        'descripcion' => 'descripción',
    ],
];
