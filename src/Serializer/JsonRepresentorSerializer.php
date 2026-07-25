<?php

namespace Amol\LaravelJsonSafe\Serializer;

use Opis\JsonSchema\Helper;

class JsonRepresentorSerializer
{
    /**
     * @template T of array{type: string, properties: array<array-key, mixed>}|null
     *
     * @param  array<array-key, mixed>|mixed  $data
     * @param  T  $schema
     */
    public static function serialize(mixed $data, ?array $schema): mixed
    {
        if ($schema === null) {
            return Helper::toJSON($data);
        }

        if ($data === null || \is_scalar($data)) {
            return $data;
        }

        $map = [];
        $isArray = true;
        $index = 0;
        if (\is_iterable($data)) {
            foreach ($data as $key => $value) {
                $keySchema = null;
                if ($schema['type'] === 'object' && \is_string($key)) {
                    /** @var T $keySchema */
                    $keySchema = $schema['properties'][$key] ?? null;
                }

                $map[$key] = self::serialize($value, $keySchema);
                if ($isArray) {
                    if ($index !== $key) {
                        $isArray = false;
                    } else {
                        $index++;
                    }
                }
            }
        }

        if ($isArray) {
            if (! $map && \is_object($data)) {
                return (object) $map;
            }

            if ($schema['type'] === 'object') {
                return (object) $map;
            }

            return $map;
        }

        return (object) $map;
    }
}
