# Workinhoo API

API backend do projeto Workinhoo, construída com Laravel 12 e PHP 8.4, utilizando PostgreSQL como banco de dados.

## Requisitos

- [Docker](https://www.docker.com/get-started) e [Docker Compose](https://docs.docker.com/compose/install/)

## Configuração do ambiente

1. Clone o repositório e acesse o diretório do projeto.

2. Copie o arquivo de variáveis de ambiente:

```bash
cp .env.example .env
```

3. Ajuste as variáveis do banco de dados no `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=workinhoo_db
DB_PORT=5432
DB_DATABASE=workinhoo_api
DB_USERNAME=workinhoo
DB_PASSWORD=secret
```

> **Atenção:** o `DB_HOST` deve ser `workinhoo_db` ou o nome do serviço no Docker Compose, não `127.0.0.1`.

4. Adicionar as credenciais do servidor de email de desenvolvimento no `.env`:

```
MAIL_MAILER=
MAIL_SCHEME=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="
```

OBS: É preciso rodar o comando `php artisan queue:work` para enviar os emails.
Faça isso dentro do container da aplicação `workinhoo_api` (seção seguinte).


## Subindo o projeto

```bash
docker compose up -d --build
```

Isso irá:
- Construir a imagem da aplicação (PHP 8.4-FPM)
- Subir o Nginx na porta **8000**
- Subir o PostgreSQL 16
- Executar automaticamente as migrations na inicialização

A API estará disponível em: `http://localhost:8000`

## Comandos úteis

### Executar comandos Artisan

```bash
docker compose exec app php artisan <comando>
```

### Executar testes

```bash
docker compose exec app php artisan test
```

### Acessar o shell do container

```bash
docker compose exec app sh
```

### Ver logs da aplicação

```bash
docker compose logs -f app
```

### Parar os containers

```bash
docker compose down
```

### Parar e remover volumes (apaga dados do banco)

```bash
docker compose down -v
```

## Estrutura Docker

| Arquivo/Diretório | Descrição |
|---|---|
| `Dockerfile` | Imagem multi-stage: instala dependências Composer e configura PHP 8.4-FPM |
| `docker-compose.yml` | Orquestra os serviços `app`, `nginx` e `db` |
| `docker/nginx/default.conf` | Configuração do Nginx como proxy reverso para o PHP-FPM |
| `docker/php/php.ini` | Configurações customizadas do PHP |
| `docker/php/opcache.ini` | Configurações do OPcache |
| `docker/entrypoint.sh` | Script de inicialização: instala dependências, gera `APP_KEY` e roda migrations |

## Serviços

| Serviço | Container | Porta |
|---|---|---|
| PHP-FPM (app) | `workinhoo_api` | 9000 (interno) |
| Nginx | `workinhoo_nginx` | 8000 |
| PostgreSQL 16 | `workinhoo_db` | 5432 (interno) |


## Para validar o projeto antes de subir um novo commit
Rode os comandos:

1. ``` ./vendor/bin/pint ```
2. ``` ./vendor/bin/pest  ```
3. ``` ./vendor/bin/phpstan analyse --memory-limit=512M --error-format=github```
