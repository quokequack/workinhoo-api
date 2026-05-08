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
    $this->portfolio = Portfolio::factory()->create(['prestador_id' => $this->prestador->id]);
});

test('retorna portfolio por uuid', function () {

    $this->actingAs($this->usuario)
        ->get("/api/portfolios/{$this->portfolio->uuid}")
        ->assertOk()
        ->assertJsonFragment(['uuid' => $this->portfolio->uuid]);
});

test('retorna 404 para uuid valido mas inexistente', function () {
    $this->actingAs($this->usuario)
        ->get('/api/portfolios/00000000-0000-0000-0000-000000000000')
        ->assertNotFound();
});

test('retorna 405 para uuid com formato invalido', function () {
    $this->actingAs($this->usuario)
        ->get('/api/portfolios/uuid-inexistente')
        ->assertMethodNotAllowed();
});

test('retorna 401 sem autenticacao', function () {
    $this->get("/api/portfolios/{$this->portfolio->uuid}")
        ->assertUnauthorized();
});
