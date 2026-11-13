<?php

use App\Exceptions\ConflictoException;
use App\Http\Middleware\JsonValido;
use App\Http\Middleware\RoleGuard;
use App\Support\ProblemDetails;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api/v1',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [JsonValido::class]);
        $middleware->alias(['rol' => RoleGuard::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Los conflictos de negocio (409) son errores esperados: no se guardan en el log
        $exceptions->dontReport([ConflictoException::class]);

        // Middleware global de errores: toda la API responde con formato RFC 9457
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return ProblemDetails::desdeExcepcion($e, $request);
            }
        });
    })->create();
