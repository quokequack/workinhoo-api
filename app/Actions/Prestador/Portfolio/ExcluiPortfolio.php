<?php

namespace App\Actions\Prestador\Portfolio;

use App\Models\Prestador\Portfolio;
use App\Support\Storage\Arquivo;

final readonly class ExcluiPortfolio
{
    public function __construct(
        private Arquivo $arquivo
    ) {}

    public function executa(Portfolio $portfolio): void
    {
        if ($portfolio->midia_path) {
            $this->arquivo->remove('', $portfolio->midia_path, 'portfolios');
        } // não recebe path por midia_path já conter o diretório e nome do arquivo

        $portfolio->delete();
    }
}
