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
        'midia_path' => $path,
    ]);
});

test('atualiza foto do portfolio', function () {
    $this->actingAs($this->usuario)
        ->patch("/api/portfolios/{$this->portfolio->uuid}/foto", [
            'midia' => UploadedFile::fake()->image('nova.jpg'),
        ])
        ->assertOk();
});

test('remove arquivo antigo ao atualizar foto', function () {
    $pathAntigo = $this->portfolio->midia_path;

    $this->actingAs($this->usuario)
        ->patch("/api/portfolios/{$this->portfolio->uuid}/foto", [
            'midia' => UploadedFile::fake()->image('nova.jpg'),
        ]);

    Storage::disk('portfolios')->assertMissing($pathAntigo);
});

test('persiste nova foto no disco', function () {
    $this->actingAs($this->usuario)
        ->patch("/api/portfolios/{$this->portfolio->uuid}/foto", [
            'midia' => UploadedFile::fake()->image('nova.jpg'),
        ]);

    $path = Portfolio::find($this->portfolio->id)->midia_path;
    Storage::disk('portfolios')->assertExists($path);
});

test('nao permite atualizar foto de portfolio de outro usuario', function () {
    $outroUsuario = Usuario::factory()->create();
    $outroPrestador = Prestador::factory()->for($outroUsuario)->create();
    $outroPortfolio = Portfolio::factory()->create(['prestador_id' => $outroPrestador->id]);

    $this->actingAs($this->usuario)
        ->patch("/api/portfolios/{$outroPortfolio->uuid}/foto", [
            'midia' => UploadedFile::fake()->image('hack.jpg'),
        ])
        ->assertForbidden();
});

test('retorna 401 sem autenticacao', function () {
    $this->patch("/api/portfolios/{$this->portfolio->id}/foto", [
        'midia' => UploadedFile::fake()->image('nova.jpg'),
    ])->assertUnauthorized();
});
