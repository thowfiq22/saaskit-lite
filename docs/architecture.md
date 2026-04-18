# Architecture

SaaSKit Lite follows a simple layered backend architecture intended to stay understandable as the project grows.

## Request Flow
1. A route points to a controller action.
2. The controller validates input through a dedicated `FormRequest`.
3. The controller delegates business logic to a service class.
4. The service coordinates persistence through repository contracts.
5. Responses are returned through a standard API response helper and Laravel API resources.

## Layer Responsibilities

### Controllers
- Keep HTTP concerns in one place.
- Accept validated request data.
- Return standard API responses.

### Services
- Hold business logic and workflow orchestration.
- Avoid framework-specific response code.

### Repositories
- Encapsulate Eloquent persistence.
- Keep filtering, querying, and eager loading consistent.

### Resources
- Normalize JSON output for API consumers.
- Keep controllers thin and predictable.

## Error Handling
- API requests always render JSON responses.
- Validation, authentication, authorization, missing routes, and unexpected failures are centralized in `bootstrap/app.php`.

## Extension Guidance
- Add new modules by mirroring the existing pattern: route, request, controller, service, repository, resource, tests.
- Prefer enums or config over hardcoded strings where a domain concept is reused.
- Keep database concerns in repositories and business rules in services.
