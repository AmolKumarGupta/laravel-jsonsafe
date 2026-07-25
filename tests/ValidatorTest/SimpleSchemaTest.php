<?php

use Amol\LaravelJsonSafe\Tests\Models\User;
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

it('can store empty data', function () {
    $user = app(UserFactory::class)->create(['preferences' => null]);

    $user->preferences = [
        'appearance' => [],
    ];
    $user->save();

    expect($user->preferences['appearance'])->toBe([]);
});

it('will work with default value', function () {
    $user = app(UserFactory::class)->create(['preferences' => []]);
    expect((array) $user->preferences)->toBe([]);
});

it('can store preferences without appearance key', function () {
    /** @var User $user */
    $user = app(UserFactory::class)->create(['preferences' => null]);
    $user->preferences = [
        'locale' => 'en',
    ];
    $user->save();

    expect($user->preferences->toArray())->toBe(['locale' => 'en']);
});

it('can store partial appearance with only theme', function () {
    $user = app(UserFactory::class)->create(['preferences' => null]);

    $user->preferences = [
        'appearance' => [
            'theme' => 'dark',
        ],
    ];
    $user->save();

    expect($user->preferences['appearance'])->toBe(['theme' => 'dark'])
        ->and($user->preferences['appearance'])->not->toHaveKey('fontSize');
});

it('can store partial appearance with only fontSize', function () {
    $user = app(UserFactory::class)->create(['preferences' => null]);

    $user->preferences = [
        'appearance' => [
            'fontSize' => 18,
        ],
    ];
    $user->save();

    expect($user->preferences['appearance'])->toBe(['fontSize' => 18])
        ->and($user->preferences['appearance'])->not->toHaveKey('theme');
});

it('can overwrite populated appearance with empty object', function () {
    $user = app(UserFactory::class)->create([
        'preferences' => [
            'appearance' => [
                'theme' => 'dark',
                'fontSize' => 16,
            ],
        ],
    ]);

    $user->preferences = [
        'appearance' => [],
    ];
    $user->save();

    expect($user->preferences['appearance'])->toBe([]);
});

it('can incrementally build appearance from empty', function () {
    $user = app(UserFactory::class)->create(['preferences' => null]);

    $user->preferences = ['appearance' => []];
    $user->save();

    $user->preferences['appearance']['theme'] = 'system';
    $user->save();

    expect($user->preferences['appearance'])->toBe(['theme' => 'system']);

    $user->preferences['appearance']['fontSize'] = 14;
    $user->save();

    expect($user->preferences['appearance'])->toBe([
        'theme' => 'system',
        'fontSize' => 14,
    ]);
});

it('can store all three valid theme values with empty fontSize context', function () {
    foreach (['light', 'dark', 'system'] as $theme) {
        $user = app(UserFactory::class)->create(['preferences' => null]);

        $user->preferences = [
            'appearance' => [
                'theme' => $theme,
            ],
        ];
        $user->save();

        expect($user->preferences['appearance']['theme'])->toBe($theme);
    }
});

it('rejects null value for theme inside appearance', function () {
    $user = app(UserFactory::class)->create(['preferences' => null]);

    $user->preferences = [
        'appearance' => [
            'theme' => null,
        ],
    ];
    $user->save();
})->throws(Exception::class);

it('rejects empty string for theme inside appearance', function () {
    $user = app(UserFactory::class)->create(['preferences' => null]);

    $user->preferences = [
        'appearance' => [
            'theme' => '',
        ],
    ];
    $user->save();
})->throws(Exception::class);
