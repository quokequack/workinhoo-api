<?php

use App\Models\Orcamento\Acordo;
use App\Models\Orcamento\PrestadorOrcamento;
use App\Models\Orcamento\Recibo;
use App\Models\Prestador\Prestador;
use App\Models\Usuario\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withHeaders([
        'Accept' => 'application/json',
        'Origin' => 'http://localhost',
    ]);

    $this->usuarioLogado = Usuario::factory()->create();

    $this->prestador = Prestador::factory()->for($this->usuarioLogado)->create();

    $this->orcamento = PrestadorOrcamento::factory()->create([
        'prestador_id' => $this->prestador->id,
    ]);

    $this->acordo = Acordo::factory()->create([
        'orcamento_id' => $this->orcamento->id,
        'finalizado' => false,
    ]);
});

test('deve finalizar o acordo e gerar um recibo com sucesso via endpoint', function () {
    $payload = [
        'data_servico' => now()->format('Y-m-d'),
    ];

    $response = $this->actingAs($this->usuarioLogado, 'sanctum')
        ->postJson("/api/acordos/{$this->acordo->id}/finalizar", $payload);

    $response->assertStatus(201);

    $response->assertJsonStructure([
        'id',
        'acordo_id',
        'data_servico',
    ]);

    expect($this->acordo->refresh()->finalizado)->toBeTruthy();

    $this->assertDatabaseHas('recibos', [
        'acordo_id' => $this->acordo->id,
    ]);
});

test('deve retornar erro 422 se a data do serviço for inválida', function () {
    $payload = [
        'data_servico' => 'data-invalida',
    ];

    $response = $this->actingAs($this->usuarioLogado, 'sanctum')
        ->postJson("/api/acordos/{$this->acordo->id}/finalizar", $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['data_servico']);
});

test('deve retornar erro 422 se tentar finalizar um acordo que já foi finalizado', function () {
    $this->acordo->update(['finalizado' => true]);

    $payload = [
        'data_servico' => '2026-06-01',
    ];

    $response = $this->actingAs($this->usuarioLogado, 'sanctum')
        ->postJson("/api/acordos/{$this->acordo->id}/finalizar", $payload);

    $response->assertStatus(422)
        ->assertJsonPath('message', 'Este acordo já foi finalizado.');
});

test('deve barrar com erro 403 se o usuário não fizer parte do orçamento (Policy)', function () {
    $usuarioIntruso = Usuario::factory()->create();

    $payload = [
        'data_servico' => '2026-06-01',
    ];

    $response = $this->actingAs($usuarioIntruso, 'sanctum')
        ->postJson("/api/acordos/{$this->acordo->id}/finalizar", $payload);

    $response->assertStatus(403);
});

test('deve barrar com erro 401 se o usuário não estiver autenticado', function () {
    $payload = [
        'data_servico' => '2026-06-01',
    ];

    $response = $this->postJson("/api/acordos/{$this->acordo->id}/finalizar", $payload);

    $response->assertStatus(401);
});

test('deve exibir os detalhes de um recibo existente', function () {
    $recibo = Recibo::factory()->create([
        'acordo_id' => $this->acordo->id,
        'data_servico' => now()->format('Y-m-d'),
    ]);

    $response = $this->actingAs($this->usuarioLogado, 'sanctum')
        ->getJson("/api/recibos/{$recibo->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'acordo_id',
            'data_servico',
        ]);
});

test('deve retornar 404 se o recibo consultado não existir', function () {
    $response = $this->actingAs($this->usuarioLogado, 'sanctum')
        ->getJson('/api/recibos/99999');

    $response->assertStatus(404);
});
