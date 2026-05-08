<?php

use App\Actions\Prestador\Portfolio\ExcluiPortfolio;
use App\Models\Prestador\Portfolio;
use App\Support\Storage\Arquivo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('portfolios');

    $this->arquivo = new Arquivo;
    $this->action = new ExcluiPortfolio($this->arquivo);
});

test('exclui portfolio', function () {
    $portfolio = Portfolio::factory()->create();

    $this->action->executa($portfolio);

    $this->assertModelMissing($portfolio);
});

test('exclui portfolio com midia e remove arquivo', function () {
    $portfolio = Portfolio::factory()->create();

    $path = UploadedFile::fake()->image('foto.jpg')
        ->storeAs($portfolio->prestador->uuid, 'foto.webp', ['disk' => 'portfolios']);

    $portfolio->update(['midia_path' => $path]);

    $this->action->executa($portfolio);

    $this->assertModelMissing($portfolio);
    Storage::disk('portfolios')->assertMissing($path);
});

test('exclui portfolio sem midia sem erro', function () {
    $portfolio = Portfolio::factory()->create(['midia_path' => null]);

    $this->action->executa($portfolio);

    $this->assertModelMissing($portfolio);
});
