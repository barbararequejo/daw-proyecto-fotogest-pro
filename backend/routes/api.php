<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\FotografiaController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\SesionController;
use Illuminate\Support\Facades\Route;

/*
| Todas las rutas tienen el prefijo /api/v1 (configurado en bootstrap/app.php).
|  - auth:api  -> authGuard: exige un JWT válido (si no, 401)
|  - rol:admin -> roleGuard: exige rol de administradora (si no, 403)
*/

// ---------- Rutas públicas ----------
Route::post('/auth/registro', [AuthController::class, 'registro'])->middleware('throttle:20,1');
Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:20,1');
Route::get('/servicios', [ServicioController::class, 'index']);
Route::get('/servicios/{id}', [ServicioController::class, 'show'])->whereNumber('id');

// ---------- Rutas privadas (cliente y admin) ----------
Route::middleware('auth:api')->group(function () {
    Route::get('/auth/yo', [AuthController::class, 'yo']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/sesiones', [SesionController::class, 'index']);
    Route::post('/sesiones', [SesionController::class, 'store']);
    Route::get('/sesiones/{id}', [SesionController::class, 'show'])->whereNumber('id');
    Route::get('/sesiones/{sesionId}/pagos', [PagoController::class, 'indexSesion'])->whereNumber('sesionId');
    Route::get('/sesiones/{sesionId}/fotografias', [FotografiaController::class, 'index'])->whereNumber('sesionId');
    Route::get('/fotografias/{id}/descargar', [FotografiaController::class, 'descargar'])->whereNumber('id');

    // ---------- Rutas solo de administradora ----------
    Route::middleware('rol:admin')->prefix('admin')->group(function () {
        Route::get('/clientes', [ClienteController::class, 'index']);
        Route::get('/clientes/{id}', [ClienteController::class, 'show'])->whereNumber('id');

        Route::get('/servicios', [ServicioController::class, 'indexAdmin']);
        Route::post('/servicios', [ServicioController::class, 'store']);
        Route::put('/servicios/{id}', [ServicioController::class, 'update'])->whereNumber('id');
        Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])->whereNumber('id');

        Route::patch('/sesiones/{id}/estado', [SesionController::class, 'actualizarEstado'])->whereNumber('id');
        Route::put('/sesiones/{id}/servicios/{servicioId}', [SesionController::class, 'actualizarLinea'])->whereNumber(['id', 'servicioId']);

        Route::get('/pagos', [PagoController::class, 'index']);
        Route::post('/sesiones/{sesionId}/pagos', [PagoController::class, 'store'])->whereNumber('sesionId');
        Route::put('/pagos/{id}', [PagoController::class, 'update'])->whereNumber('id');

        Route::post('/sesiones/{sesionId}/fotografias', [FotografiaController::class, 'store'])->whereNumber('sesionId');
        Route::delete('/fotografias/{id}', [FotografiaController::class, 'destroy'])->whereNumber('id');
    });
});
