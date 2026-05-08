<?php

namespace App\Http\Controllers\Prestador;

use App\Actions\Prestador\Portfolio\CriaPortfolio;
use App\Actions\Prestador\Portfolio\EditaFotoPortfolio;
use App\Actions\Prestador\Portfolio\EditaPortfolio;
use App\Actions\Prestador\Portfolio\ExcluiPortfolio;
use App\Actions\Prestador\Portfolio\ExibePortfolio;
use App\DTO\Prestador\NovoPortfolioDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Prestador\Portfolio\StorePortfolioRequest;
use App\Http\Requests\Prestador\Portfolio\UpdateFotoPortfolioRequest;
use App\Http\Requests\Prestador\Portfolio\UpdatePortfolioRequest;
use App\Http\Resources\Prestador\PortfolioResource;
use App\Models\Prestador\Portfolio;
use App\Models\Prestador\Prestador;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Prestador $prestador, ExibePortfolio $action): JsonResponse
    {
        return $this->sucesso(PortfolioResource::collection($action->porPrestador($prestador)));
    }

    // por uuid
    public function show(Portfolio $portfolio): JsonResponse // preestador usado para resolver bind
    {
        return $this->sucesso(PortfolioResource::make($portfolio));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortfolioRequest $request, Prestador $prestador, CriaPortfolio $action): JsonResponse
    {
        $dto = new NovoPortfolioDTO(
            prestadorUUID: $prestador->uuid,
            descricao: $request->input('descricao'),
            midia: $request->file('midia'),
        );

        return $this->criado(PortfolioResource::make($action->executa($dto)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePortfolioRequest $request, Portfolio $portfolio, EditaPortfolio $action): JsonResponse
    {
        Gate::authorize('update', $portfolio);

        $dto = new NovoPortfolioDTO(
            prestadorUUID: $portfolio->prestador->uuid,
            descricao: $request->input('descricao'),
            midia: $request->file('midia'),
        );

        return $this->sucesso(PortfolioResource::make($action->executa($portfolio, $dto)));
    }

    // atualiza foto individualmente
    public function updateFoto(UpdateFotoPortfolioRequest $request, Portfolio $portfolio, EditaFotoPortfolio $action): JsonResponse
    {
        Gate::authorize('update', $portfolio);

        return $this->sucesso(PortfolioResource::make($action->executa($portfolio, $request->file('midia'))));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio, ExcluiPortfolio $action): JsonResponse
    {
        Gate::authorize('delete', $portfolio);
        $action->executa($portfolio);

        return $this->semConteudo();
    }
}
