# Backend

This folder contains the Laravel 11 API backend for SaaSKit Lite.

## Highlights
- Laravel Sanctum authentication
- Role-based access control
- Product CRUD with filtering and pagination
- Controller -> service -> repository structure
- Standardized JSON response format
- Centralized API exception handling
- Deterministic demo seeders and feature tests

## Common Commands
```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan migrate:fresh --seed
php artisan serve
php artisan test
```

## Default API Base URL
```text
http://127.0.0.1:8000/api/v1
```

## Demo Accounts
- `admin@example.com` / `password`
- `user@example.com` / `password`
- `ops@example.com` / `password`
- `analyst@example.com` / `password`

## Seeded Dataset
- 4 users
- 6 products

See the root [README](../README.md) for full setup and project-level documentation.
