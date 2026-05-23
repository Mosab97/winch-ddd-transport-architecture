# WINCH DDD Transport Architecture

A Laravel and Vue system for assigning transport orders to the best available driver using a DDD-inspired architecture.

The backend exposes clean JSON APIs for orders, drivers, and assignment. The frontend is a Vue 3 single-page app that consumes those APIs and provides a simple transport operations interface.

## Requirements

- Docker
- Laravel Sail
- PostgreSQL
- PHP 8.3+ and Composer, if running without Docker
- Node.js and npm, for frontend assets

## Architecture

The application separates business behavior from HTTP presentation:

- `src/Domain` contains entities, enums, Actions, Services, Contracts, and domain exceptions.
- `src/Presentation` contains API controllers, requests, resources, response formatting, routes, and exception rendering.
- `resources/js` contains the Vue frontend, router, API services, composables, pages, and reusable components.

Controllers are intentionally thin. They validate requests, call Actions, and return resources or response envelopes. Assignment logic lives in the domain layer.

Additional notes document the architectural trade-offs and high-load decision requested by the assignment.

## Additional Documentation

- [Architecture Notes](docs/ARCHITECTURE_NOTES.md)
- [High Load Decision](docs/HIGH_LOAD_DECISION.md)

## Run With Docker / Laravel Sail

```bash
docker run --rm -v "$PWD":/app -w /app composer:2 composer install
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

The app is exposed on `http://localhost:8080` by default.

Install and build frontend assets:

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

## Run Without Docker

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Set `DB_HOST=127.0.0.1` and your local PostgreSQL credentials in `.env` before migrating.

## Run Tests

```bash
./vendor/bin/sail artisan test
```

Without Docker:

```bash
php artisan test
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

If `npm run build` runs in an environment without access to `fonts.bunny.net`, Laravel's Bunny font integration may fail before the Vue build starts. Allow network access for the build or change the font strategy before production delivery.

## API Endpoints

```http
GET /api/orders?status=pending&page=1
POST /api/orders/{id}/assign
GET /api/drivers?page=1
GET /api/drivers/{id}/orders?status=assigned&page=1
```

## API Response Format

Successful responses use a consistent envelope:

```json
{
  "status": "success",
  "message": "Orders fetched successfully",
  "data": [],
  "code": 200
}
```

Paginated responses also include `meta` and `links`. Error responses use the same envelope with `status: "error"` and may include validation `errors`.

## Concurrency Handling

Order assignment is wrapped in a database transaction. The order row is locked with `lockForUpdate` before checking whether it is still pending, and candidate driver rows are locked before assignment. This protects against concurrent requests assigning the same order or driver.
