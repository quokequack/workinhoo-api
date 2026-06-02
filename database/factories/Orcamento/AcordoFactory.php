<?php

namespace Database\Factories\Orcamento;

use App\Models\Orcamento\Acordo;
use App\Models\Orcamento\PrestadorOrcamento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Acordo>
 */
class AcordoFactory extends Factory
{
    protected $model = Acordo::class;

    public function definition(): array
    {
        return [
            'orcamento_id' => PrestadorOrcamento::factory(),
            'finalizado' => false,
        ];
    }
}
