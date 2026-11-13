<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistroRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function registro(RegistroRequest $request): JsonResponse
    {
        return $this->ok($this->authService->registrar($request->validated()), 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $datos = $request->validated();

        return $this->ok($this->authService->login($datos['email'], $datos['password']));
    }

    public function yo(Request $request): JsonResponse
    {
        return $this->ok($request->user()->load('cliente'));
    }

    public function logout(): Response
    {
        $this->authService->logout();

        return response()->noContent(); // 204
    }
}
