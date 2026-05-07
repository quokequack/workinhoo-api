<?php

namespace App\Actions\Prestador\Portfolio;

use App\DTO\Prestador\NovoPortfolioDTO;
use App\Models\Prestador\Portfolio;
use App\Models\Prestador\Prestador;
use App\Support\Storage\Arquivo;
use App\Support\ValueObjects\UUID;

final readonly class EditaPortfolio
{
    public function __construct(
        private Arquivo $arquivo
    ) {}

    public function executa(Portfolio $portfolio, NovoPortfolioDTO $dto): Portfolio
    {
        $midiaPath = $portfolio->midia_path;
        $prestador = Prestador::where('uuid', $dto->prestadorUUID)->firstOrFail();

        if ($dto->midia) {
            $this->arquivo->remove('', $midiaPath, 'portfolios');

            $midiaID = UUID::cria();
            $midiaPath = $this->arquivo->persiste($dto->midia, $prestador->uuid, "{$midiaID->recupera()}.webp", ['disk' => 'portfolios']);

        }

        $portfolio->update([...$dto->toArray($prestador->id), 'midia_path' => $midiaPath]);

        return $portfolio->refresh();
    }
}
