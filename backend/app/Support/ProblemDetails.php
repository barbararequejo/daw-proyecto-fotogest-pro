<?php

namespace App\Support;

use App\Exceptions\ConflictoException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Convierte cualquier excepción de la API en una respuesta de error
 * con el formato RFC 9457 (Problem Details for HTTP APIs).
 */
class ProblemDetails
{
    private const TIPOS = [
        400 => ['bad-request', 'Petición mal formada'],
        401 => ['unauthorized', 'No autenticado'],
        403 => ['forbidden', 'Acceso denegado'],
        404 => ['not-found', 'Recurso no encontrado'],
        405 => ['method-not-allowed', 'Método no permitido'],
        409 => ['conflict', 'Conflicto con el estado del recurso'],
        422 => ['validation-failed', 'Error de validación de parámetros'],
        429 => ['too-many-requests', 'Demasiadas peticiones'],
        500 => ['internal-error', 'Error interno del servidor'],
    ];

    public static function desdeExcepcion(Throwable $e, Request $request): JsonResponse
    {
        $errores = null;

        if ($e instanceof ValidationException) {
            $status = 422;
            $detalle = 'Los datos enviados no cumplen las reglas de validación.';
            $errores = [];
            foreach ($e->errors() as $campo => $mensajes) {
                foreach ($mensajes as $mensaje) {
                    $errores[] = ['field' => $campo, 'message' => $mensaje];
                }
            }
        } elseif ($e instanceof AuthenticationException) {
            $status = 401;
            $detalle = $e->getMessage() === 'Unauthenticated.'
                ? 'Falta el token de acceso, no es válido o ha caducado.'
                : $e->getMessage();
        } elseif ($e instanceof ConflictoException) {
            $status = 409;
            $detalle = $e->getMessage();
        } elseif ($e instanceof QueryException && ($e->errorInfo[0] ?? null) === '23000') {
            // Violación de restricción (UNIQUE o clave foránea) detectada por la base de datos
            $status = 409;
            $detalle = 'La operación viola una restricción de integridad de la base de datos.';
        } elseif ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();
            $detalle = $e->getMessage() ?: self::TIPOS[$status][1] ?? 'Error';
            if ($status === 404 && str_contains($detalle, 'No query results')) {
                $detalle = 'El recurso solicitado no existe.';
            } elseif ($status === 404 && str_contains($detalle, 'could not be found')) {
                $detalle = 'La ruta solicitada no existe.';
            }
        } else {
            // Error no controlado: se registra en el log, pero nunca se envía la traza al cliente
            $status = 500;
            $detalle = 'Se ha producido un error inesperado. Inténtalo de nuevo más tarde.';
        }

        return self::respuesta($status, $detalle, $request, $errores);
    }

    public static function respuesta(int $status, string $detalle, Request $request, ?array $errores = null): JsonResponse
    {
        [$slug, $titulo] = self::TIPOS[$status] ?? ['error', 'Error'];

        $cuerpo = [
            'type' => "https://fotogest.pro/errors/{$slug}",
            'title' => $titulo,
            'status' => $status,
            'detail' => $detalle,
            'instance' => '/'.ltrim($request->path(), '/'),
            'timestamp' => now()->utc()->format('Y-m-d\TH:i:s.v\Z'),
        ];

        if ($errores !== null) {
            $cuerpo['errors'] = $errores;
        }

        return response()->json($cuerpo, $status, ['Content-Type' => 'application/problem+json'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
