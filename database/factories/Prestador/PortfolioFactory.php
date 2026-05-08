<?php

namespace Database\Factories\Prestador;

use App\Models\Prestador\Portfolio;
use App\Models\Prestador\Prestador;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Portfolio>
 */
class PortfolioFactory extends Factory
{
    protected $model = Portfolio::class;

    public function definition(): array
    {
        return [
            'prestador_id' => Prestador::factory(),
            'descricao' => fake()->sentence(),
            'midia_path' => null,
        ];
    }
}
