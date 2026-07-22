<?php

use Amol\LaravelJsonSafe\Validator\ModelMethodResolver;
use Illuminate\Database\Eloquent\Model;

it('resolve single word', function () {
    $resolved = ModelMethodResolver::resolve('name');
    expect($resolved)->toBe(ModelMethodResolver::$prefix.'Name');
});

it('resolve camel case word', function () {
    $resolved = ModelMethodResolver::resolve('camelCase');
    expect($resolved)->toBe(ModelMethodResolver::$prefix.'CamelCase');
});

it('resolve pascal case word', function () {
    $resolved = ModelMethodResolver::resolve('PascalCase');
    expect($resolved)->toBe(ModelMethodResolver::$prefix.'PascalCase');
});

it('resolve snake case word', function () {
    $resolved = ModelMethodResolver::resolve('snake_case');
    expect($resolved)->toBe(ModelMethodResolver::$prefix.'SnakeCase');
});

it('resolve upper cased word', function () {
    $resolved = ModelMethodResolver::resolve('UPPERCASE');
    expect($resolved)->toBe(ModelMethodResolver::$prefix.'Uppercase');
});

/**
 * Existence Check
 */
it('found public resolved method in model', function () {
    $methodName = ModelMethodResolver::resolve('name');
    expect(ModelMethodResolver::doesResolveMethodExists(TmpModel::class, $methodName))
        ->toBe(true);
});

class TmpModel extends Model
{
    public function schemaOfName(): array
    {
        return [];
    }
}
