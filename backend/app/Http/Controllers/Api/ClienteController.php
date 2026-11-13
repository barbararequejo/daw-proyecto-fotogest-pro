<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClienteService;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
    public function __construct(private ClienteService $clienteService)
    {
    }

    public function index(): JsonResponse
    {
        return $this->ok($this->clienteService->listar());
    }

    public function show(int $id): JsonResponse
    {
        return $this->ok($this->clienteService->obtener($id));
    }
}
