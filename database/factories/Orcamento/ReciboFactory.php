<?php

namespace Database\Factories\Orcamento;

use App\Models\Orcamento\Acordo;
use App\Models\Orcamento\Recibo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReciboFactory extends Factory
{
    protected $model = Recibo::class;

    public function definition(): array
    {
        return [
            'acordo_id' => Acordo::factory(),
            'data_servico' => $this->faker->date(),
        ];
    }
}
