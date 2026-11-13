<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LineaServicioRequest;
use App\Http\Requests\SesionEstadoRequest;
use App\Http\Requests\SesionRequest;
use App\Services\SesionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SesionController extends Controller
{
    public function __construct(private SesionService $sesionService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $estado = $request->query('estado');

        return $this->ok($this->sesionService->listar($request->user(), $estado));
    }

    public function show(Request $request, int $id): JsonResponse
    {
        return $this->ok($this->sesionService->obtener($id, $request->user()));
    }

    public function store(SesionRequest $request): JsonResponse
    {
        return $this->ok($this->sesionService->crear($request->user(), $request->validated()), 201);
    }

    public function actualizarEstado(SesionEstadoRequest $request, int $id): JsonResponse
    {
        return $this->ok($this->sesionService->actualizarEstado($id, $request->validated()));
    }

    public function actualizarLinea(LineaServicioRequest $request, int $id, int $servicioId): JsonResponse
    {
        return $this->ok($this->sesionService->actualizarLinea($id, $servicioId, $request->validated()));
    }
}
