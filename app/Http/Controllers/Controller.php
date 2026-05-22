<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    public function sucesso(mixed $data = null): JsonResponse
    {
        return response()->json($data);
    }

    public function criado(mixed $registro): JsonResponse
    {
        return response()->json($registro, Response::HTTP_CREATED);
    }

    public function semConteudo(): JsonResponse
    {
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
