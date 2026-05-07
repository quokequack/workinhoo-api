<?php

namespace App\Actions\Prestador\Portfolio;

use App\Models\Prestador\Portfolio;
use App\Support\Storage\Arquivo;

final readonly class ExcluiFotoPortfolio
{
    public function __construct(
        private Arquivo $arquivo
    ) {}

    public function executa(Portfolio $portfolio): Portfolio
    {
        // não recebe path por midia_path já conter o diretório e nome do arquivo
        $this->arquivo->remove('', $portfolio->midia_path, 'portfolios');

        $portfolio->update(['midia_path' => null]);

        return $portfolio->refresh();
    }
}
