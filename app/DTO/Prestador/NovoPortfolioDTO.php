<?php

namespace App\DTO\Prestador;

use Illuminate\Http\UploadedFile;

final readonly class NovoPortfolioDTO
{
    public function __construct(
        public string $prestadorUUID,
        public string $descricao,
        public ?UploadedFile $midia = null, // midia_path será gerado pelo action
    ) {}

    public function toArray(int $prestadorID): array
    {
        return [
            'prestador_id' => $prestadorID,
            'descricao' => $this->descricao,
        ];
    }
}
