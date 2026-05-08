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

    $path = UploadedFile::fake()->image('foto.jpg')
        ->storeAs($this->prestador->uuid, 'foto.webp', ['disk' => 'portfolios']);

    $this->portfolio = Portfolio::factory()->create([
        'prestador_id' => $this->prestador->id,
        'midia_path' => $path,
    ]);
});

test('exclui portfolio', function () {
    $this->actingAs($this->usuario)
        ->delete("/api/portfolios/{$this->portfolio->uuid}")
        ->assertNoContent();

    $this->assertModelMissing($this->portfolio);
});

test('remove arquivo do disco ao excluir portfolio', function () {
    $path = $this->portfolio->midia_path;

    $this->actingAs($this->usuario)
        ->delete("/api/portfolios/{$this->portfolio->uuid}");

    Storage::disk('portfolios')->assertMissing($path);
});

test('nao exclui portfolio de outro usuario', function () {
    $outroUsuario = Usuario::factory()->create();
    $outroPrestador = Prestador::factory()->for($outroUsuario)->create();
    $outroPortfolio = Portfolio::factory()->create(['prestador_id' => $outroPrestador->id]);

    $this->actingAs($this->usuario)
        ->delete("/api/portfolios/{$outroPortfolio->uuid}")
        ->assertForbidden();

    $this->assertModelExists($outroPortfolio);
});

test('retorna 401 sem autenticacao', function () {
    $this->delete("/api/portfolios/{$this->portfolio->uuid}")
        ->assertUnauthorized();
});
