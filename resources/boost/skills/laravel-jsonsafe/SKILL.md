---
name: laravel-jsonsafe
description: Build and work with laravel-jsonsafe features, including mutable JSON casting and JSON Schema validation.
---

# Laravel JsonSafe Development

## When to use this skill

Use this skill when working with mutable JSON Eloquent casts, JSON Schema validation for JSON columns, or when you need to store and manipulate nested JSON data in Laravel models without manual reassignment.

## Features

### Mutable JSON casting

Cast a JSON column to `JsonSafe` to wrap it in a `JsonSafeable` (`ArrayObject` subclass). Nested key reads and writes persist on `save()` without reassigning the entire column.

```php
use Amol\LaravelJsonSafe\JsonSafe;

class User extends Model
{
    protected $casts = [
        'extras' => JsonSafe::class,
    ];
}
```

Usage:

```php
$user->extras['settings']['theme'] = 'dark';
$user->save(); // persists the nested change
```

### JSON Schema validation

Define a public `schemaOf{Key}()` method on your model to enable validation. The column key is converted to StudlyCase and prefixed with `schemaOf`.

```php
class User extends Model
{
    protected $casts = [
        'preferences' => JsonSafe::class,
    ];

    public function schemaOfPreferences(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'appearance' => [
                    'type' => 'object',
                    'properties' => [
                        'theme' => [
                            'type' => 'string',
                            'enum' => ['light', 'dark', 'system'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
```

Validation runs automatically on `set()`. Invalid data throws an `Exception` before any database write.

Key mapping rules:
- `preferences` → `schemaOfPreferences()`
- `my_column` → `schemaOfMyColumn()`
- `UPPERCASE` → `schemaOfUppercase()`

### Serialization

`toArray()` and `toJson()` return the underlying plain array:

```php
$user->extras->toArray(); // ['theme' => 'dark']
```

## Important invariants

- `JSON_THROW_ON_ERROR` is always used — invalid JSON throws before any DB write.
- `JSON_BIGINT_AS_STRING` is set on decode to preserve large integers as strings.
- No attribute-level dirty tracking — mutations through `JsonSafeable` require an explicit `save()`.
- Schemas follow the [JSON Schema](https://opis.io/json-schema/2.x/structure.html) standard via `opis/json-schema`.
- Columns without a `schemaOf{Key}()` method are not validated.
