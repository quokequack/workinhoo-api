<?php

use App\Models\Prestador\Portfolio;
use App\Models\Prestador\Prestador;
use App\Models\Usuario\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('portfolios');
    $this->withHeaders([
        'Accept' => 'application/json',
        'Origin' => 'http://localhost',
    ]);

    $this->usuario = Usuario::factory()->create();
    $this->prestador = Prestador::factory()->for($this->usuario)->create();

    $path = UploadedFile::fake()->image('antiga.jpg')
        ->storeAs("{$this->prestador->uuid}", 'antiga.webp', ['disk' => 'portfolios']);

    $this->portfolio = Portfolio::factory()->create([
        'prestador_id' => $this->prestador->id,
        'descricao' => 'Descrição antiga',
        'midia_path' => $path,
    ]);
});

test('edita descricao do portfolio', function () {
    $this->actingAs($this->usuario)
        ->put("/api/portfolios/{$this->portfolio->uuid}", [
            'descricao' => 'Nova descrição',
        ])
        ->assertOk()
        ->assertJsonFragment(['descricao' => 'Nova descrição']);

    $this->assertDatabaseHas('portfolios_prestadores', [
        'id' => $this->portfolio->id,
        'descricao' => 'Nova descrição',
    ]);
});

test('edita midia do portfolio e remove arquivo antigo', function () {
    $pathAntigo = $this->portfolio->midia_path;

    $this->actingAs($this->usuario)
        ->put("/api/portfolios/{$this->portfolio->uuid}", [
            'descricao' => 'Nova descrição',
            'midia' => UploadedFile::fake()->image('nova.jpg'),
        ])
        ->assertOk();

    Storage::disk('portfolios')->assertMissing($pathAntigo);
});

test('mantem midia quando nao envia nova', function () {
    $pathAntigo = $this->portfolio->midia_path;

    $this->actingAs($this->usuario)
        ->put("/api/portfolios/{$this->portfolio->uuid}", [
            'descricao' => 'Nova descrição',
        ])
        ->assertOk();

    Storage::disk('portfolios')->assertExists($pathAntigo);
});

test('nao permite editar portfolio de outro usuario', function () {
    $outroUsuario = Usuario::factory()->create();
    $outroPrestador = Prestador::factory()->for($outroUsuario)->create();
    $outroPortfolio = Portfolio::factory()->create(['prestador_id' => $outroPrestador->id]);

    $this->actingAs($this->usuario)
        ->put("/api/portfolios/{$outroPortfolio->uuid}", ['descricao' => 'hack'])
        ->assertForbidden();
});

test('retorna 401 sem autenticacao', function () {
    $this->put("/api/portfolios/{$this->portfolio->uuid}", [
        'descricao' => 'Nova descrição',
    ])->assertUnauthorized();
});
