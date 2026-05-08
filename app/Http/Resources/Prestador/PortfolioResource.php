<?php

namespace App\Http\Resources\Prestador;

use App\Models\Prestador\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Portfolio */
class PortfolioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'descricao' => $this->descricao,
            'midia_url' => $this->midia_path
                ? asset('storage/'.$this->midia_path)
                : null,
        ];
    }
}
