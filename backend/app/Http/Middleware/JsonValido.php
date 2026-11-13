<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Si la petición dice enviar JSON pero el cuerpo no es un JSON válido, responde 400 Bad Request.
 */
class JsonValido
{
    public function handle(Request $request, Closure $next): Response
    {
        $contenido = $request->getContent();

        if ($request->isJson() && $contenido !== '') {
            json_decode($contenido);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new BadRequestHttpException('El cuerpo de la petición no es un JSON válido.');
            }
        }

        return $next($request);
    }
}
