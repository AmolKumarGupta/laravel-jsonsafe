# Tech Stack

## Runtime

| Dependency | Version | Purpose |
|---|---|---|
| PHP | ^8.3 | Minimum version |
| illuminate/contracts | ^11.0 \|\| ^12.0 \|\| ^13.0 | Laravel Eloquent `CastsAttributes` interface |
| spatie/laravel-package-tools | ^1.16 | Service provider scaffolding, config publishing |

## Dev

| Dependency | Version | Purpose |
|---|---|---|
| pestphp/pest | ^4.0 | Test framework |
| pestphp/pest-plugin-laravel | ^4.0 | Laravel integration for Pest |
| pestphp/pest-plugin-arch | ^4.0 | Architecture tests |
| orchestra/testbench | ^9–11 | Package testing without full Laravel app |
| laravel/legacy-factories | ^1.4 | Factory support for testbench |
| laravel/pint | ^1.14 | Code style (PSR-12) |
| larastan/larastan | ^3.10 | PHPStan level 9 for Laravel |
| nunomaduro/collision | ^8.8 | Pretty test output |

## Tools

| Tool | Config | Scope |
|---|---|---|
| PHPStan | `phpstan.neon.dist` | Level 9, scans `src/` only |
| Pint | (default config) | PSR-12 style |
| Pest | `phpunit.xml.dist` | Random order, strict mode |

## CI

GitHub Actions matrix: PHP 8.3–8.5 × Laravel 12–13. Runs `vendor/bin/pest --ci`. Triggers on push to `main` and PRs to `dev`.
