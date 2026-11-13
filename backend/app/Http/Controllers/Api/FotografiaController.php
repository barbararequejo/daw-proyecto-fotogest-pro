<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FotografiaRequest;
use App\Services\FotografiaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FotografiaController extends Controller
{
    public function __construct(private FotografiaService $fotografiaService)
    {
    }

    public function index(Request $request, int $sesionId): JsonResponse
    {
        return $this->ok($this->fotografiaService->listarDeSesion($sesionId, $request->user()));
    }

    public function store(FotografiaRequest $request, int $sesionId): JsonResponse
    {
        $foto = $this->fotografiaService->subir($sesionId, $request->file('foto'), $request->validated('descripcion'));

        return $this->ok($foto, 201);
    }

    public function descargar(Request $request, int $id): StreamedResponse
    {
        return $this->fotografiaService->descargar($id, $request->user());
    }

    public function destroy(int $id): Response
    {
        $this->fotografiaService->eliminar($id);

        return response()->noContent();
    }
}
