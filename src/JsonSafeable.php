<?php

namespace Amol\LaravelJsonSafe;

use Illuminate\Database\Eloquent\Casts\ArrayObject;

/**
 * @extends ArrayObject<int|string, mixed>
 */
class JsonSafeable extends ArrayObject
{
    /**
     * @param  array<int|string, mixed>  $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data, \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Get the array that should be JSON serialized.
     *
     * @return array<int|string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }
}
