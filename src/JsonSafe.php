<?php 

namespace Amol\LaravelJsonSafe;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<JsonSafeable, mixed>
 */
class JsonSafe implements CastsAttributes
{

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     * @return JsonSafeable|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) return null;
        if (! is_string($value)) return null;

        /** @var array<int|string, mixed> $data */
        $data = \json_decode($value, true, flags: \JSON_THROW_ON_ERROR | \JSON_BIGINT_AS_STRING);
        return new JsonSafeable($data);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_array($value)) {
            $value = new JsonSafeable($value);
        }
        return \json_encode($value, \JSON_THROW_ON_ERROR);
    }

    /**
     * @param  array<string, mixed>  $attributes
     *
     * @return array<int|string, mixed>
     */
    public function serialize(Model $model, string $key, JsonSafeable $value, array $attributes): array
    {
        return $value->getArrayCopy();
    }

}
