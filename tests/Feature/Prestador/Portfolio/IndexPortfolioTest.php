<?php

use App\Models\Prestador\Portfolio;
use App\Models\Prestador\Prestador;
use App\Models\Usuario\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withHeaders([
        'Accept' => 'application/json',
        'Origin' => 'http://localhost',
    ]);

    $this->usuario = Usuario::factory()->create();
    $this->prestador = Prestador::factory()->for($this->usuario)->create();
});

test('retorna portfolios do prestador autenticado', function () {
    Portfolio::factory()->count(3)->create(['prestador_id' => $this->prestador->id]);

    $response = $this->actingAs($this->usuario)
        ->get("/api/prestadores/{$this->prestador->id}/portfolios");

    $response->assertOk()
        ->assertJsonCount(3);
});

test('retorna somente portfolios do prestador correto', function () {
    $outroPrestador = Prestador::factory()->create();
    Portfolio::factory()->count(2)->create(['prestador_id' => $this->prestador->id]);
    Portfolio::factory()->count(3)->create(['prestador_id' => $outroPrestador->id]);

    $response = $this->actingAs($this->usuario)
        ->get("/api/prestadores/{$this->prestador->id}/portfolios");

    $response->assertOk()
        ->assertJsonCount(2);
});

test('retorna 401 sem autenticacao', function () {
    $this->get("/api/prestadores/{$this->prestador->id}/portfolios")
        ->assertUnauthorized();
});
