# AGENTS.md

## What this is

Laravel Eloquent cast package (`amol/laravel-jsonsafe`). PHP 8.3+, Laravel 11–13.

## Architecture

- `src/JsonSafe.php` — Eloquent cast implementing `CastsAttributes` (get/set/serialize)
- `src/JsonSafeable.php` — `ArrayObject` subclass
- `src/LaravelJsonSafeServiceProvider.php` — Spatie-based, publishes only `config/jsonsafe.php`
- `src/Validator/Validator.php` — JSON Schema validation using `opis/json-schema` (validates on set)
- `src/Validator/ModelMethodResolver.php` — Resolves `schemaOf{Key}()` methods on models
- `src/Serializer/JsonRepresentorSerializer.php` — Converts PHP arrays to JSON Schema-compatible format
- Namespace: `Amol\LaravelJsonSafe\`

## Test setup

- Pest v4 with Orchestra Testbench (`tests/TestCase.php`)
- Test DB migration: `tests/database/migrations/add_extras_in_users_table.php` (adds `extras` JSON column)
- Tests run in random order with strict settings (fail on warning/risky/empty suite)
- Validator tests: `tests/ValidatorTest/SimpleSchemaTest.php`, `tests/ValidatorTest/ModelMethodResolverTest.php`

## References

- `.ai/ARCHITECTURE.md` — Full architecture doc (data flow, invariants, dependencies, dev tooling)
- `.ai/TECH-STACK.md` — Runtime and dev dependencies, tooling
