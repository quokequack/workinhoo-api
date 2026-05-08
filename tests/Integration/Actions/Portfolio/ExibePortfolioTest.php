<?php

use App\Actions\Prestador\Portfolio\ExibePortfolio;
use App\Models\Prestador\Portfolio;
use App\Models\Prestador\Prestador;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->prestador = Prestador::factory()->create();
    $this->action = new ExibePortfolio;
});

test('retorna portfolios do prestador', function () {
    Portfolio::factory()->count(3)->create(['prestador_id' => $this->prestador->id]);

    $portfolios = $this->action->porPrestador($this->prestador);

    expect($portfolios)->toBeInstanceOf(Collection::class)
        ->and($portfolios)->toHaveCount(3);
});

test('retorna somente portfolios do prestador correto', function () {
    $outroPrestador = Prestador::factory()->create();

    Portfolio::factory()->count(2)->create(['prestador_id' => $this->prestador->id]);
    Portfolio::factory()->count(3)->create(['prestador_id' => $outroPrestador->id]);

    $portfolios = $this->action->porPrestador($this->prestador);

    expect($portfolios)->toHaveCount(2);
});

test('retorna portfolio por uuid', function () {
    $portfolio = Portfolio::factory()->create(['prestador_id' => $this->prestador->id]);

    $resultado = $this->action->porUuid($portfolio->uuid);

    expect($resultado->uuid)->toBe($portfolio->uuid);
});

test('lanca excecao para uuid inexistente', function () {
    $this->action->porUuid('uuid-inexistente');
})->throws(ModelNotFoundException::class);

test('retorna portfolios ordenados do mais recente', function () {
    $primeiro = Portfolio::factory()->create(['prestador_id' => $this->prestador->id]);
    $segundo = Portfolio::factory()->create(['prestador_id' => $this->prestador->id]);

    $portfolios = $this->action->porPrestador($this->prestador);

    expect($portfolios->first()->id)->toBe($segundo->id)
        ->and($portfolios->last()->id)->toBe($primeiro->id);
});
