<?php

use Orchestra\Testbench\Factories\UserFactory;

it('show appearance.theme as light', function () {
    $user = app(UserFactory::class)->create([
        'preferences' => [
            'appearance' => [
                'theme' => 'light',
            ],
        ],
    ]);

    expect($user->preferences['appearance'])->toBe([
        'theme' => 'light',
    ]);
});

it('should throw error on invalid json', function () {
    $user = app(UserFactory::class)->create([
        'preferences' => [
            'appearance' => [
                'theme' => 'light',
            ],
        ],
    ]);

    $user->preferences['appearance']['theme'] = 'test';
    $user->save();
})->throws(Exception::class);

it('show appearance.fontSize as 12', function () {
    $user = app(UserFactory::class)->create(['preferences' => null]);

    $user->preferences = [
        'appearance' => [
            'theme' => 'light',
            'fontSize' => 12,
        ],
    ];
    $user->save();

    expect($user->preferences['appearance']['fontSize'])->toBe(12);
});
