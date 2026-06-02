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

test('login retorna 401 quando não autenticado mesmo com credenciais válidas', function () {
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

    $response->assertUnauthorized();
});

test('login retorna 401 quando não autenticado e senha está incorreta', function () {
    Usuario::factory()->create(['email' => 'usuario@example.com']);

    $response = $this->post('/api/auth/login', [
        'credencial' => 'usuario@example.com',
        'senha' => 'senha-errada',
    ]);

    $response->assertUnauthorized();
});

test('login retorna 401 quando não autenticado e email não existe', function () {
    $response = $this->post('/api/auth/login', [
        'credencial' => 'naoexiste@example.com',
        'senha' => 'qualquersenha',
    ]);

    $response->assertUnauthorized();
});

test('login retorna 401 quando não autenticado e credencial não é enviada', function () {
    $response = $this->post('/api/auth/login', ['senha' => 'senha123']);

    $response->assertUnauthorized();
});

test('login retorna 401 quando não autenticado e senha não é enviada', function () {
    $response = $this->post('/api/auth/login', ['credencial' => 'usuario@example.com']);

    $response->assertUnauthorized();
});

test('login retorna 401 quando não autenticado e credencial não é email', function () {
    $response = $this->post('/api/auth/login', [
        'credencial' => 'nao-e-email',
        'senha' => 'senha123',
    ]);

    $response->assertUnauthorized();
});
