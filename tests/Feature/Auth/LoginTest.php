<?php

use App\Models\Usuario\Usuario;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withHeaders([
        'Accept' => 'application/json',
    ]);
});

test('login autentica com credenciais válidas', function () {
    $usuario = Usuario::factory()->create([
        'email' => 'usuario@example.com',
        'password' => 'senha123',
    ]);

    $response = $this
        ->withoutMiddleware(ValidateCsrfToken::class)
        ->withSession([])
        ->withHeader('Origin', 'http://localhost')
        ->post('/api/auth/login', [
            'credencial' => 'usuario@example.com',
            'senha' => 'senha123',
        ]);

    $response->assertOk()
        ->assertJson([
            'uuid' => $usuario->uuid,
            'nome' => $usuario->nome,
            'email' => $usuario->email,
            'is_prestador' => $usuario->is_prestador,
        ]);
});

test('login retorna 422 quando senha está incorreta', function () {
    Usuario::factory()->create(['email' => 'usuario@example.com']);

    $response = $this->post('/api/auth/login', [
        'credencial' => 'usuario@example.com',
        'senha' => 'senha-errada',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['credencial']);
});

test('login retorna 422 quando email não existe', function () {
    $response = $this->post('/api/auth/login', [
        'credencial' => 'naoexiste@example.com',
        'senha' => 'qualquersenha',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['credencial']);
});

test('login retorna 422 quando credencial não é enviada', function () {
    $response = $this->post('/api/auth/login', ['senha' => 'senha123']);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['credencial']);
});

test('login retorna 422 quando senha não é enviada', function () {
    $response = $this->post('/api/auth/login', ['credencial' => 'usuario@example.com']);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['senha']);
});

test('login retorna 422 quando credencial não é email', function () {
    $response = $this->post('/api/auth/login', [
        'credencial' => 'nao-e-email',
        'senha' => 'senha123',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['credencial']);
});
