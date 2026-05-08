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
});

test('cria portfolio com dados validos', function () {
    $this->actingAs($this->usuario)
        ->post("/api/prestadores/{$this->prestador->id}/portfolios", [
            'descricao' => 'Meu trabalho',
            'midia' => UploadedFile::fake()->image('foto.jpg'),
        ])
        ->assertCreated()
        ->assertJsonFragment(['descricao' => 'Meu trabalho']);

    $this->assertDatabaseHas('portfolios_prestadores', [
        'prestador_id' => $this->prestador->id,
        'descricao' => 'Meu trabalho',
    ]);
});

test('persiste arquivo no disco ao criar', function () {
    $this->actingAs($this->usuario)
        ->post("/api/prestadores/{$this->prestador->id}/portfolios", [
            'descricao' => 'Meu trabalho',
            'midia' => UploadedFile::fake()->image('foto.jpg'),
        ]);

    $path = Portfolio::where('prestador_id', $this->prestador->id)->first()->midia_path;
    Storage::disk('portfolios')->assertExists($path);
});

test('retorna uuid do portfolio criado', function () {
    $response = $this->actingAs($this->usuario)
        ->post("/api/prestadores/{$this->prestador->id}/portfolios", [
            'descricao' => 'Meu trabalho',
            'midia' => UploadedFile::fake()->image('foto.jpg'),
        ]);

    $response->assertCreated();
    expect($response->json('uuid'))->not->toBeNull();
});

test('retorna 401 sem autenticacao', function () {
    $this->post("/api/prestadores/{$this->prestador->id}/portfolios", [
        'midia' => UploadedFile::fake()->image('foto.jpg'),
    ])->assertUnauthorized();
});
