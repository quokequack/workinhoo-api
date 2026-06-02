<?php

namespace Database\Factories\Orcamento;

use App\Models\Orcamento\PrestadorOrcamento;
use App\Models\Prestador\Especialidade;
use App\Models\Prestador\Prestador;
use App\Models\Usuario\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PrestadorOrcamento>
 */
class PrestadorOrcamentoFactory extends Factory
{
    protected $model = PrestadorOrcamento::class;

    public function definition(): array
    {
        return [
            'solicitante_id' => Usuario::factory(),
            'prestador_id' => Prestador::factory(),
            'especialidade_prestador_id' => Especialidade::factory(),
            'descricao' => $this->faker->sentence(),
            'valor' => $this->faker->randomFloat(2, 100, 1000),
            'aceito' => null,
        ];
    }
}
