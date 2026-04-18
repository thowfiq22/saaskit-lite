# Changelog

All notable changes to SaaSKit Lite are documented here.

## [v1.0.0] - 2026-04-18

Initial public release of SaaSKit Lite.

### Added

- Laravel 11 API backend scaffolded for SaaS and admin-style applications.
- Laravel Sanctum authentication with register, login, logout, and current-user endpoints.
- Role-based access control with seeded `admin` and `user` accounts.
- Dashboard statistics endpoint with role-aware output.
- Product CRUD module with filtering, sorting, and pagination.
- Controller -> service -> repository architecture for maintainable backend growth.
- Standardized API response format for success, errors, and pagination metadata.
- Global API validation and exception handling for consistent JSON responses.
- Deterministic seeders for demo users and products.
- Feature tests covering authentication, dashboard behavior, products, and seeder idempotency.
- Repository documentation, roadmap, changelog, contribution guide, and screenshot placeholders.
- Optional Flutter starter app structure for login and dashboard integration.
