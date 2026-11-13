<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Restringe una ruta a los roles indicados. Uso en rutas: ->middleware('rol:admin')
 * Se ejecuta después de auth:api, así que el usuario ya está autenticado.
 * Si el rol no coincide se responde 403 Forbidden.
 */
class RoleGuard
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! in_array($request->user()?->rol, $roles, true)) {
            throw new AccessDeniedHttpException('Tu rol no tiene permiso para realizar esta operación.');
        }

        return $next($request);
    }
}
