<?php

use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    $this->withHeaders(['Accept' => 'application/json']);
});

test('verificacao de email retorna 422 quando codigo tem formato invalido', function () {
    $response = $this->postJson('/api/auth/email/verificar', ['codigo' => 'token-valido']);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['codigo']);
});

test('verificacao de email retorna 422 quando codigo é invalido', function () {
    $response = $this->postJson('/api/auth/email/verificar', ['codigo' => 'ffffffff']);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['token']);
});

test('reenvio de verificacao retorna 404 quando email não existe', function () {
    $response = $this->post('/api/auth/email/verificacao', ['email' => 'inexistente@example.com']);

    $response->assertNotFound();

    Mail::assertNothingSent();
});
