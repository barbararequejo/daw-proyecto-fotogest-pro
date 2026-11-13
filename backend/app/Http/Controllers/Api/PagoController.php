<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PagoRequest;
use App\Services\PagoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function __construct(private PagoService $pagoService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->ok($this->pagoService->listar($request->query('estado')));
    }

    public function indexSesion(Request $request, int $sesionId): JsonResponse
    {
        return $this->ok($this->pagoService->listarDeSesion($sesionId, $request->user()));
    }

    public function store(PagoRequest $request, int $sesionId): JsonResponse
    {
        return $this->ok($this->pagoService->crear($sesionId, $request->validated()), 201);
    }

    public function update(PagoRequest $request, int $id): JsonResponse
    {
        return $this->ok($this->pagoService->actualizar($id, $request->validated()));
    }
}
