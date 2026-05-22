<?php

use App\Models\Usuario\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withHeaders([
        'Accept' => 'application/json',
        'Origin' => 'http://localhost',
    ]);
});

test('login com credenciais válidas retorna 200 com dados do usuário', function () {
    $usuario = Usuario::factory()->create([
        'email' => 'usuario@example.com',
        'password' => 'senha123',
    ]);

    $response = $this->post('/api/auth/login', [
        'credencial' => 'usuario@example.com',
        'senha' => 'senha123',
    ]);

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('uuid', $usuario->uuid)
            ->where('nome', $usuario->nome)
            ->where('email', $usuario->email)
            ->where('is_prestador', false)
        );
});

test('login com senha incorreta retorna 422', function () {
    Usuario::factory()->create(['email' => 'usuario@example.com']);

    $response = $this->post('/api/auth/login', [
        'credencial' => 'usuario@example.com',
        'senha' => 'senha-errada',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['credencial']);
});

test('login com email inexistente retorna 422', function () {
    $response = $this->post('/api/auth/login', [
        'credencial' => 'naoexiste@example.com',
        'senha' => 'qualquersenha',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['credencial']);
});

test('login sem credencial retorna 422', function () {
    $response = $this->post('/api/auth/login', ['senha' => 'senha123']);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['credencial']);
});

test('login sem senha retorna 422', function () {
    $response = $this->post('/api/auth/login', ['credencial' => 'usuario@example.com']);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['senha']);
});

test('login com credencial que não é email retorna 422', function () {
    $response = $this->post('/api/auth/login', [
        'credencial' => 'nao-e-email',
        'senha' => 'senha123',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['credencial']);
});
