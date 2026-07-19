# Architecture

## Problem

Laravel JSON columns are decoded to plain arrays, requiring the developer to reassign
the entire column whenever a single nested key changes. This package wraps decoded JSON
in a mutable `ArrayObject` subclass so array-key reads and writes on an Eloquent attribute
persist to the database on `save()` without manual reassignment.

## Data flow

```
DB column (JSON string)
  ──JsonSafe::get()──▶  JsonSafeable (ArrayObject)
                           │  $user->extras['key'] = $val
                           │  $user->save()
  ◀──JsonSafe::set()──   json_encode($value)
  ──JsonSafe::serialize()──▶  plain array  (toArray / toJson)
```

`get()` returns `null` for non-string or non-array JSON. `set()` accepts a plain array
or `JsonSafeable`; plain arrays are wrapped before encoding. Both directions use
`JSON_THROW_ON_ERROR`.

## Invariants

- **No config options are used at runtime.** `config/jsonsafe.php` exists but is empty.
  `JsonSafe` has no constructor parameters or external dependencies.
- **JSON encode/decode never silently swallows errors.** `JSON_THROW_ON_ERROR` is used in
  both directions. Invalid JSON will throw before any DB write occurs.
- **`JSON_BIGINT_AS_STRING`** is set on decode only, preserving large integers as strings
  rather than losing precision to float conversion.
- **No attribute-level dirty tracking.** Mutations through `JsonSafeable` are not detected
  as Eloquent dirty attributes; the column must be explicitly saved via `save()` or the
  mutation will be lost.
- **`tests/database/factories` does not exist.** `TestCase::loadFactoriesUsing()` references
  it, which means any test relying on `UserFactory` (from Orchestra Testbench) will work,
  but adding a local factory requires creating that directory first.
- **No README usage examples.** Documentation of the cast API is absent; the test suite is
  the primary usage reference.


## Dev tooling

| Command | Tool |
|---|---|
| `composer run test` | Pest (`vendor/bin/pest`) |
| `composer run format` | Pint (`vendor/bin/pint`) |
| `composer run analyse` | PHPStan level 9 (`vendor/bin/phpstan`) |

CI matrix: PHP 8.3–8.5 × Laravel 12–13, `pest --ci`, triggered on push to `main`
and PRs to `dev`.
