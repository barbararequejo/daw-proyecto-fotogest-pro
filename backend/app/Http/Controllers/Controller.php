<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /** Respuesta de éxito uniforme: { "data": ... } */
    protected function ok(mixed $datos, int $status = 200): JsonResponse
    {
        return response()->json(['data' => $datos], $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
