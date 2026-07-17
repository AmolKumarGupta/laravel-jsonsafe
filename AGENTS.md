# AGENTS.md

## What this is

Laravel Eloquent cast package (`amol/laravel-jsonsafe`) that wraps JSON column values in a safe `ArrayObject` subclass (`JsonSafeable`) supporting property-style access. PHP 8.3+, Laravel 11–13.

## Commands

- **Test:** `composer run test` (runs `vendor/bin/pest`)
- **Format:** `composer run format` (runs `vendor/bin/pint`)
- **Analyse:** `composer run analyse` (runs `vendor/bin/phpstan`)

No PHPStan or static analysis configured. Pint is the only code quality tool.

## CI

GitHub Actions matrix in `.github/workflows/run-tests.yml`: PHP 8.3–8.5 × Laravel 12–13, runs `vendor/bin/pest --ci`. Triggers on push to `main` and PRs to `dev`, filtered to PHP/workflow/config files.

## Architecture

- `src/JsonSafe.php` — Eloquent cast implementing `CastsAttributes` (get/set/serialize)
- `src/JsonSafeable.php` — `ArrayObject` subclass with `ARRAY_AS_PROPS` for property access
- `src/LaravelJsonSafeServiceProvider.php` — Spatie-based, publishes only `config/jsonsafe.php`
- Namespace: `Amol\LaravelJsonSafe\`

## Test setup

- Pest v4 with Orchestra Testbench (`tests/TestCase.php`)
- Test DB migration: `tests/database/migrations/add_extras_in_users_table.php` (adds `extras` JSON column)
- `tests/database/factories` is referenced in TestCase but **does not exist** — creating factory-based tests requires creating this directory first
- Tests run in random order with strict settings (fail on warning/risky/empty suite)

## Gotchas
- No usage documentation exists in README yet