<?php

namespace App\Http\Controllers\Orcamento;

use App\Actions\Orcamento\GeraRecibo;
use App\DTO\Orcamento\ReciboDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orcamento\GeraReciboRequest;
use App\Http\Resources\Orcamento\ReciboResource;
use App\Models\Orcamento\Acordo;
use App\Models\Orcamento\Recibo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ReciboController extends Controller
{
    public function show(Recibo $recibo): JsonResponse
    {
        return $this->sucesso(ReciboResource::make($recibo));
    }

    public function store(GeraReciboRequest $request, Acordo $acordo, GeraRecibo $action): JsonResponse
    {
        $dto = ReciboDTO::fromRequest($request, $acordo->id);

        $recibo = $action->executa($dto);

        return $this->criado(ReciboResource::make($recibo));
    }
}
