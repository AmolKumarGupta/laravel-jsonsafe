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
        /**
         * Add support for UPPERCASE value to Uppercase for laravel verson below 13
         */
        $normalizedKey = \preg_replace_callback(
            '/(^|[-_ \s])([A-Z]+)(?=[-_ \s]|$)/u',
            fn ($m) => $m[1].Str::lower($m[2]),
            $key
        );

        if (! \is_string($normalizedKey)) {
            return self::$prefix.Str::studly($key);
        }

        return self::$prefix.Str::studly($normalizedKey);
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
