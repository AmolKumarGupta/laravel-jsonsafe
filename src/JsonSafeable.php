<?php

namespace Amol\LaravelJsonSafe;

use Illuminate\Database\Eloquent\Casts\ArrayObject;

class JsonSafeable extends ArrayObject
{

    public function __construct(array $data) 
    {
        parent::__construct($data, \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Get the array that should be JSON serialized.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }

}
