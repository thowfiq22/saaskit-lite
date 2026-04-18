# Contributing

Thanks for contributing to SaaSKit Lite.

## Contribution Flow

1. Fork the repository.
2. Create a branch from `main`.
3. Make your changes in a focused scope.
4. Add or update tests when behavior changes.
5. Update docs if commands, setup, or API behavior changes.
6. Open a pull request with a clear summary and testing notes.

Recommended branch naming:

- `feature/product-tags`
- `fix/auth-token-handling`
- `docs/readme-refresh`

## Local Setup

1. Follow the setup steps in the root [README](./README.md).
2. Work from the `backend/` directory for API changes.
3. Run the test suite before opening a pull request:

```bash
php artisan test
```

## Pull Request Expectations

- Keep pull requests focused and reviewable.
- Explain the problem, the approach, and any tradeoffs.
- Reference related issues when applicable.
- Include screenshots or example payloads for user-facing changes.
- Confirm local verification steps in the PR description.

## Code Style Expectations

- Follow the existing controller -> service -> repository structure.
- Keep controllers thin and move business logic into services.
- Keep database querying and filtering logic inside repositories.
- Use Laravel Form Requests for validation.
- Keep API responses aligned with the standard project response format.
- Prefer descriptive naming over abbreviations.
- Avoid hardcoded configuration when a config value, enum, or constant is more appropriate.
- Add comments only where they improve clarity for non-obvious logic.

## Testing Expectations

- Add or update feature tests for API behavior changes.
- Keep seeded demo data deterministic when adjusting seeders.
- Verify pagination, validation, and authorization behavior for changed endpoints.

## Issue Reporting

When opening an issue, include:

- expected behavior
- actual behavior
- reproduction steps
- environment details such as PHP version, database, and OS
- relevant logs, stack traces, or request payloads when possible

## Security

Do not report sensitive vulnerabilities in public issues. Share them privately with the maintainers instead.
