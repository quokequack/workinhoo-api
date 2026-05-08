<?php

namespace App\Actions\Prestador\Portfolio;

use App\Models\Prestador\Portfolio;
use App\Support\Storage\Arquivo;
use App\Support\ValueObjects\UUID;
use Illuminate\Http\UploadedFile;

final readonly class EditaFotoPortfolio
{
    public function __construct(
        private Arquivo $arquivo
    ) {}

    public function executa(Portfolio $portfolio, UploadedFile $midia): Portfolio
    {
        // Remove o arquivo antigo
        $this->arquivo->remove('', $portfolio->midia_path, 'portfolios');

        // Persiste o novo
        $midiaID = UUID::cria();
        $midiaPath = $this->arquivo->persiste($midia, $portfolio->prestador->uuid, "{$midiaID->recupera()}.webp", ['disk' => 'portfolios']);

        $portfolio->update(['midia_path' => $midiaPath]);

        return $portfolio->refresh();
    }
}
