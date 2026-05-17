<?php

use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    $this->withHeaders(['Accept' => 'application/json']);
});

test('retorna 422 quando token nao eh enviado no formato esperado', function () {
    $response = $this->postJson('/api/auth/email/verificar', ['codigo' => 'token-valido']);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['token']);
});

test('retorna erro para token invalido', function () {
    $response = $this->postJson('/api/auth/email/verificar', ['token' => 'ffffffff']);

    $response->assertStatus(500);
});

test('reenvio retorna 500 para email inexistente pelo fluxo atual', function () {
    $response = $this->post('/api/auth/email/verificacao', ['email' => 'inexistente@example.com']);

    $response->assertInternalServerError();

    Mail::assertNothingSent();
});
