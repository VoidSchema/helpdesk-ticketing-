# Constraints

Last reviewed: 2026-09-07 by @shan

## Floor (always enforced, no setup required)

- No new suppression comments: `@ts-ignore`, `eslint-disable`, `# noqa`, `# type: ignore`
- No unimplemented stubs: `throw new Error("Not implemented")`, empty `catch {}`
- No skipped or deleted tests without a reason in the commit message
- No secrets in source
- This file does not get weakened to make a change pass

## Enforced with numbers

| Dimension | Rule | Checked by | Runs at |
|-----------|------|-----------|---------|
| Types | Zero type errors | PHPStan level 5 | every edit |
| Lint | Zero errors from our config | Laravel Pint | every edit |
| Secrets | No secrets in source | No manual review | every edit |
| Coverage | Changed lines ≥ 80% covered | PHPUnit + coverage | task end |
| Security: deps | Nothing at high or above | `composer audit` | CI |

## Measured, not yet enforced

| Metric | Today | Direction |
|--------|-------|-----------|
| Project coverage | 0% (new project) | must grow |
| Database migrations | 3 (default Laravel) | must not break |

## Exceptions

| ID | Rule | Path | Reason | Owner | Expires |
|----|------|------|--------|-------|---------|
| — | — | — | No exceptions yet | — | — |

## Development Commands

```bash
# Lint (via Docker)
docker compose exec php-fpm ./vendor/bin/pint --test

# Test (via Docker)
docker compose exec php-fpm php artisan test

# Static analysis (install PHPStan first)
docker compose exec php-fpm ./vendor/bin/phpstan analyse

# Build frontend
npm run build

# Dev server (run locally)
npm run dev
```

## Notes

- PHPStan is not yet installed; add as dev dependency when ready
- PHPUnit is included in Laravel default; use for testing
- Pint is Laravel's code style fixer (includes PHP-CS-Fixer)
