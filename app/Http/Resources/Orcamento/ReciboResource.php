<?php

namespace App\Http\Resources\Orcamento;

use App\Models\Orcamento\Recibo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Recibo */
class ReciboResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'acordo_id' => $this->acordo_id,
            'data_servico' => $this->data_servico,
        ];
    }
}
