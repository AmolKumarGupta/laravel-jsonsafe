<?php 

namespace Amol\LaravelJsonSafe;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class JsonSafe implements CastsAttributes
{

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
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

    public function serialize($model, string $key, $value, array $attributes)
    {
        return $value->getArrayCopy();
    }

}
