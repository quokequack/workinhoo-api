<?php

namespace App\Actions\Prestador\Portfolio;

use App\Models\Prestador\Portfolio;
use App\Models\Prestador\Prestador;

final readonly class ExibePortfolio
{
    public function porPrestador(Prestador $prestador)
    {
        return Portfolio::query()
            ->where('prestador_id', $prestador->id)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function porUuid(string $uuid): Portfolio
    {
        return Portfolio::query()
            ->where('uuid', $uuid)
            ->firstOrFail();
    }
}
