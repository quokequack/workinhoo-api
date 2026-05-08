<?php

use App\Actions\Prestador\Portfolio\CriaPortfolio;
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
    $this->action = new CriaPortfolio($this->arquivo);
});

test('cria portfolio', function () {
    $dto = new NovoPortfolioDTO(
        prestadorUUID: $this->prestador->uuid,
        descricao: 'Meu trabalho',
        midia: UploadedFile::fake()->image('foto.jpg'),
    );

    $portfolio = $this->action->executa($dto);

    expect($portfolio)->toBeInstanceOf(Portfolio::class);
});

test('cria portfolio com dados válidos', function () {
    $dto = new NovoPortfolioDTO(
        prestadorUUID: $this->prestador->uuid,
        descricao: 'Meu trabalho',
        midia: UploadedFile::fake()->image('foto.jpg'),
    );

    $portfolio = $this->action->executa($dto);

    expect($portfolio->descricao)->toBe($dto->descricao)
        ->and($portfolio->prestador_id)->toBe($this->prestador->id)
        ->and($portfolio->midia_path)->not->toBeNull();
});

test('gera uuid automaticamente', function () {
    $dto = new NovoPortfolioDTO(
        prestadorUUID: $this->prestador->uuid,
        descricao: 'Meu trabalho',
        midia: UploadedFile::fake()->image('foto.jpg'),
    );

    $portfolio = $this->action->executa($dto);

    expect($portfolio->uuid)->not->toBeNull()->toBeString();
});

test('persiste arquivo no disco', function () {
    $dto = new NovoPortfolioDTO(
        prestadorUUID: $this->prestador->uuid,
        descricao: 'Meu trabalho',
        midia: UploadedFile::fake()->image('foto.jpg'),
    );

    $portfolio = $this->action->executa($dto);

    Storage::disk('portfolios')->assertExists($portfolio->midia_path);
});
