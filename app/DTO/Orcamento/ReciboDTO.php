<?php

namespace App\DTO\Orcamento;

use App\Http\Requests\Orcamento\GeraReciboRequest;
use Carbon\Carbon;

readonly class ReciboDTO
{
    public function __construct(
        public int $acordo_id,
        private Carbon $data_servico,
    ) {}

    public static function fromRequest(GeraReciboRequest $request, int $acordoId): self
    {
        return new self(
            $acordoId,
            Carbon::parse($request->validated('data_servico')),
        );
    }

    public function toArray(): array
    {
        return [
            'acordo_id'    => $this->acordo_id,
            'data_servico' => $this->data_servico,
        ];
    }
}
