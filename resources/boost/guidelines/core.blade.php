## Laravel JsonSafe

This package provides mutable JSON column casting for Laravel Eloquent with JSON Schema validation via `opis/json-schema`.

### Features

- **Mutable JSON casting**: Wrap JSON columns in `JsonSafeable` (an `ArrayObject` subclass) so nested key reads and writes persist on `save()` without manual reassignment.
- **JSON Schema validation**: Define validation schemas via `schemaOf{Key}()` methods on models. Data is validated on every `set()` call using `opis/json-schema`.
- **Serialization**: `toArray()` and `toJson()` return the underlying plain array.

### Casting a column

Use `Amol\LaravelJsonSafe\JsonSafe` in your model's `$casts` array:

```php
use Amol\LaravelJsonSafe\JsonSafe;

class User extends Model
{
    protected $casts = [
        'extras' => JsonSafe::class,
        'preferences' => JsonSafe::class,
    ];
}
```

Read and write nested keys directly — changes persist on `save()`:

```php
$user->extras['theme'] = 'dark';
$user->save();
```

### Validation schemas

Define a public `schemaOf{Key}()` method on your model to enable validation. The column key is converted to StudlyCase and prefixed with `schemaOf` (e.g., `preferences` → `schemaOfPreferences()`).

```php
use Amol\LaravelJsonSafe\JsonSafe;

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
                        'fontSize' => [
                            'type' => 'integer',
                            'minimum' => 10,
                            'maximum' => 24,
                        ],
                    ],
                ],
            ],
        ];
    }
}
```

If the data violates the schema, an `Exception` is thrown before the database write:

```php
$user->preferences['appearance']['theme'] = 'invalid';
$user->save(); // throws Exception
```

Key mapping rules:
- `preferences` → `schemaOfPreferences()`
- `my_column` → `schemaOfMyColumn()`
- `UPPERCASE` → `schemaOfUppercase()`

Columns without a schema method are not validated.

### Conventions

- Schemas follow the [JSON Schema](https://opis.io/json-schema/2.x/structure.html) standard via `opis/json-schema`.
- `JSON_THROW_ON_ERROR` is always used — invalid JSON throws before any DB write.
- `JSON_BIGINT_AS_STRING` is set on decode to preserve large integers as strings.
- No attribute-level dirty tracking — mutations through `JsonSafeable` require an explicit `save()`.
