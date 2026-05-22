# WINCH DDD Transport Architecture

A Laravel backend system for assigning transport orders to the best available driver using a DDD-inspired architecture.

## Requirements

- Docker
- Laravel Sail
- PostgreSQL
- PHP 8.3+ and Composer, only if running without Docker

## Run With Docker / Laravel Sail

```bash
docker run --rm -v "$PWD":/app -w /app composer:2 composer install
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

The app is exposed on `http://localhost:8080` by default.

## Run Without Docker

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Set `DB_HOST=127.0.0.1` and your local PostgreSQL credentials in `.env` before migrating.

## Run Tests

```bash
./vendor/bin/sail artisan test
```

## Frontend

The WINCH interface is a Vue 3 single-page app built with the Composition API. It uses Vue Router for `/orders`, `/drivers`, and `/drivers/:driverId/orders`, and communicates with Laravel through the `/api/*` endpoints.

Install frontend dependencies:

```bash
npm install
```

Run the frontend locally with Vite:

```bash
npm run dev
```

Build production assets:

```bash
npm run build
```

## API Endpoints

```http
GET /api/orders?status=pending&page=1
POST /api/orders/{id}/assign
GET /api/drivers?page=1
GET /api/drivers/{id}/orders?status=assigned&page=1
```
