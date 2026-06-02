<?php

use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    $this->withHeaders(['Accept' => 'application/json']);
});

test('verificacao de email retorna 401 quando não autenticado e codigo tem formato invalido', function () {
    $response = $this->postJson('/api/auth/email/verificar', ['codigo' => 'token-valido']);

    $response->assertUnauthorized();
});

test('verificacao de email retorna 401 quando não autenticado e codigo é invalido', function () {
    $response = $this->postJson('/api/auth/email/verificar', ['codigo' => 'ffffffff']);

    $response->assertUnauthorized();
});

test('reenvio de verificacao retorna 401 quando não autenticado e email não existe', function () {
    $response = $this->post('/api/auth/email/verificacao', ['email' => 'inexistente@example.com']);

    $response->assertUnauthorized();

    Mail::assertNothingSent();
});
