<?php

use App\Actions\Orcamento\GeraRecibo;
use App\DTO\Orcamento\ReciboDTO;
use App\Models\Orcamento\Acordo;
use App\Models\Orcamento\Recibo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->acordo = Acordo::factory()->create([
        'finalizado' => false,
    ]);

    $this->action = new GeraRecibo;
});

test('gera recibo com sucesso', function () {
    $dto = new ReciboDTO(
        acordo_id: $this->acordo->id,
        data_servico: Carbon::parse('2026-05-28'),
    );

    $recibo = $this->action->executa($dto);

    expect($recibo)->toBeInstanceOf(Recibo::class);
});

test('persiste recibo com os dados corretos no banco de dados', function () {
    $dataServico = Carbon::parse('2026-05-28');

    $dto = new ReciboDTO(
        acordo_id: $this->acordo->id,
        data_servico: $dataServico,
    );

    $recibo = $this->action->executa($dto);

    expect($recibo->acordo_id)->toBe($this->acordo->id);

    // Validação resiliente ao formato string/timestamp do SQLite
    $this->assertDatabaseHas('recibos', [
        'acordo_id' => $this->acordo->id,
    ]);

    expect(Carbon::parse($recibo->data_servico)->format('Y-m-d'))->toBe($dataServico->format('Y-m-d'));
});

test('atualiza o status do acordo para finalizado', function () {
    $dto = new ReciboDTO(
        acordo_id: $this->acordo->id,
        data_servico: now(),
    );

    $this->action->executa($dto);

    expect($this->acordo->refresh()->finalizado)->toBeTruthy();

    $this->assertDatabaseHas('acordos', [
        'id' => $this->acordo->id,
        'finalizado' => 1, // 1 representa true no ecossistema SQLite de testes
    ]);
});

test('lança exceção quando o acordo não existe', function () {
    $dto = new ReciboDTO(
        acordo_id: 99999,
        data_servico: now(),
    );

    $this->action->executa($dto);
})->throws(ModelNotFoundException::class);
