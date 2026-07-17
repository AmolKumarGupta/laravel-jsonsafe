<?php

use Amol\LaravelJsonSafe\JsonSafeable;
use Orchestra\Testbench\Factories\UserFactory;

it('can test', function () {
    $user = app(UserFactory::class)->create([
        'extras' => ['key' => 'value'],
    ]);

    expect($user->extras)
        ->toBeInstanceOf(JsonSafeable::class)
        ->toHaveProperty('key');
});