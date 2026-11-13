<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicioRequest;
use App\Services\ServicioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ServicioController extends Controller
{
    public function __construct(private ServicioService $servicioService)
    {
    }

    // Público
    public function index(): JsonResponse
    {
        return $this->ok($this->servicioService->listar());
    }

    // Público
    public function show(int $id): JsonResponse
    {
        return $this->ok($this->servicioService->obtener($id));
    }

    // Admin: catálogo completo, incluidos los servicios desactivados
    public function indexAdmin(): JsonResponse
    {
        return $this->ok($this->servicioService->listar(incluirNoDisponibles: true));
    }

    public function store(ServicioRequest $request): JsonResponse
    {
        return $this->ok($this->servicioService->crear($request->validated()), 201);
    }

    public function update(ServicioRequest $request, int $id): JsonResponse
    {
        return $this->ok($this->servicioService->actualizar($id, $request->validated()));
    }

    public function destroy(int $id): Response
    {
        $this->servicioService->eliminar($id);

        return response()->noContent();
    }
}
