<?php

use App\Actions\Prestador\Portfolio\EditaPortfolio;
use App\DTO\Prestador\NovoPortfolioDTO;
use App\Models\Prestador\Portfolio;
use App\Models\Prestador\Prestador;
use App\Support\Storage\Arquivo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('portfolios');

    $this->prestador = Prestador::factory()->create();
    $this->arquivo = new Arquivo;
    $this->action = new EditaPortfolio($this->arquivo);

    $path = UploadedFile::fake()->image('antiga.jpg')
        ->storeAs($this->prestador->uuid, 'antiga.webp', ['disk' => 'portfolios']);

    $this->portfolio = Portfolio::factory()->create([
        'prestador_id' => $this->prestador->id,
        'descricao' => 'Descrição antiga',
        'midia_path' => $path,
    ]);
});

test('edita descricao sem nova midia', function () {
    $dto = new NovoPortfolioDTO(
        prestadorUUID: $this->prestador->uuid,
        descricao: 'Nova descrição',
    );

    $portfolio = $this->action->executa($this->portfolio, $dto);

    expect($portfolio->descricao)->toBe('Nova descrição')
        ->and($portfolio->midia_path)->toBe($this->portfolio->midia_path);
});

test('edita midia e remove arquivo antigo', function () {
    $pathAntigo = $this->portfolio->midia_path;

    $dto = new NovoPortfolioDTO(
        prestadorUUID: $this->prestador->uuid,
        descricao: 'Descrição antiga',
        midia: UploadedFile::fake()->image('nova.jpg'),
    );

    $portfolio = $this->action->executa($this->portfolio, $dto);

    expect($portfolio->midia_path)->not->toBe($pathAntigo);
    Storage::disk('portfolios')->assertMissing($pathAntigo);
    Storage::disk('portfolios')->assertExists($portfolio->midia_path);
});

test('edita descricao e midia simultaneamente', function () {
    $dto = new NovoPortfolioDTO(
        prestadorUUID: $this->prestador->uuid,
        descricao: 'Descrição atualizada',
        midia: UploadedFile::fake()->image('nova.jpg'),
    );

    $portfolio = $this->action->executa($this->portfolio, $dto);

    expect($portfolio->descricao)->toBe('Descrição atualizada')
        ->and($portfolio->midia_path)->not->toBeNull();
    Storage::disk('portfolios')->assertExists($portfolio->midia_path);
});

test('mantem arquivo no disco quando nao envia nova midia', function () {
    $pathAntigo = $this->portfolio->midia_path;

    $dto = new NovoPortfolioDTO(
        prestadorUUID: $this->prestador->uuid,
        descricao: 'Nova descrição',
    );

    $this->action->executa($this->portfolio, $dto);

    Storage::disk('portfolios')->assertExists($pathAntigo);
});
