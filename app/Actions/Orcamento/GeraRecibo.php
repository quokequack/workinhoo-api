<?php

namespace App\Actions\Orcamento;

use App\DTO\Orcamento\ReciboDTO;
use App\Models\Orcamento\Acordo;
use App\Models\Orcamento\Recibo;
use Illuminate\Support\Facades\DB;

class GeraRecibo
{
    public function executa(ReciboDTO $dto): Recibo
    {
        return DB::transaction(function () use ($dto) {

            $acordo = Acordo::findOrFail($dto->acordo_id);
            $acordo->update([
                'finalizado' => true,
            ]);

            return Recibo::create($dto->toArray());
        });
    }
}
