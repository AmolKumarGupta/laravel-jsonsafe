<?php

namespace Amol\LaravelJsonSafe\Validator;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ReflectionMethod;

class ModelMethodResolver
{
    public static string $prefix = 'schemaOf';

    public static function resolve(string $key): string
    {
        return self::$prefix.Str::studly($key, normalize: true);
    }

    public static function doesResolveMethodExists(Model|string $model, string $methodName): bool
    {
        if (method_exists($model, $methodName)) {
            $reflection = new ReflectionMethod($model, $methodName);

            return $reflection->isPublic();
        }

        return false;
    }
}
