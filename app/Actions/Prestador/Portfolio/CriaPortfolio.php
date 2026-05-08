<?php

namespace App\Actions\Prestador\Portfolio;

use App\DTO\Prestador\NovoPortfolioDTO;
use App\Models\Prestador\Portfolio;
use App\Models\Prestador\Prestador;
use App\Support\Storage\Arquivo;
use App\Support\ValueObjects\UUID;

final readonly class CriaPortfolio
{
    public function __construct(
        private Arquivo $arquivo
    ) {}

    public function executa(NovoPortfolioDTO $dto): Portfolio
    {
        $prestador = Prestador::where('uuid', $dto->prestadorUUID)->firstOrFail();

        $midiaID = UUID::cria();
        $midiaPath = $this->arquivo->persiste($dto->midia, $prestador->uuid, "{$midiaID->recupera()}.webp", ['disk' => 'portfolios']);

        return Portfolio::query()->create([...$dto->toArray($prestador->id), 'midia_path' => $midiaPath]);
    }
}
