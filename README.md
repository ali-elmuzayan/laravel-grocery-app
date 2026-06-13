# Grocery Marketplace API

A Laravel 13 backend for a grocery marketplace platform.

The API includes:
- JWT authentication with refresh token rotation
- Product catalog and category management
- Cart and checkout workflows
- Orders and delivery timeline tracking
- Payment intents, installment records, escrow, and payout flows
- Role-based access control for `user`, `vendor`, and `admin`

## Tech Stack

- PHP `^8.3`
- Laravel `^13.8`
- MySQL/SQLite via Eloquent and migrations
- JWT auth via `tymon/jwt-auth`
- Roles/permissions via `spatie/laravel-permission`
- Queue-ready jobs for notifications and payout processing

## Project Structure

- `app/Domain`: domain services (`Catalog`, `Cart`, `Orders`, `Payments`)
- `app/Http/Controllers/Api/V1`: API controllers
- `database/migrations`: schema for auth, catalog, cart, order, delivery, and payments
- `database/seeders`: roles/permissions and demo data
- `docs`: architecture, security baseline, and performance checklist

## Quick Start

### 1) Install dependencies

```bash
composer install
npm install
```

### 2) Configure environment

```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### 3) Prepare database

You can use SQLite (default) or switch to MySQL in `.env`.

For SQLite:

```bash
touch database/database.sqlite
php artisan migrate --seed
```

### 4) Run the app

```bash
composer run dev
```

This starts:
- Laravel HTTP server
- Queue listener
- Log tailing
- Vite dev server

## Demo Seed Accounts

After `php artisan migrate --seed`:

- Admin: `admin@grocery.test` / `password`
- Vendor: `vendor@grocery.test` / `password`
- User: `customer@grocery.test` / `password`

## API Overview

Base prefix: `/api/v1`

### Auth
- `POST /auth/register`
- `POST /auth/login`
- `POST /auth/refresh`
- `POST /auth/logout`
- `GET /auth/me`

### Catalog
- `GET /catalog/products`
- `GET /catalog/categories`

### Cart
- `GET /cart`
- `POST /cart/items`
- `DELETE /cart/items/{item}`

### Orders
- `POST /orders/checkout`
- `GET /orders`
- `GET /orders/{order}`

### Payments
- `POST /payments/intents`
- `POST /payments/intents/{paymentIntent}/capture`
- `POST /payments/payouts/request`

### Delivery
- `GET /deliveries/orders/{order}/timeline`

### Admin
- `POST /admin/categories`
- `PATCH /admin/products/{product}/approve`
- `PATCH /admin/products/{product}/reject`

## Quality Checks

```bash
composer test
composer lint
```

## Deployment Notes

Target runtime and ops guidance is documented in:
- `docs/architecture.md`
- `docs/security.md`
- `docs/performance-checklist.md`

## License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
