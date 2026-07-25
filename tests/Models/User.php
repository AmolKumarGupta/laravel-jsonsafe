<?php

namespace Amol\LaravelJsonSafe\Tests\Models;

use Amol\LaravelJsonSafe\JsonSafe;
use Amol\LaravelJsonSafe\JsonSafeable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property JsonSafeable $preferences
 */
class User extends Authenticatable
{
    protected $guarded = [];

    protected $casts = [
        'extras' => JsonSafe::class,
        'preferences' => JsonSafe::class,
    ];

    public function schemaOfPreferences(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'appearance' => [
                    'type' => 'object',
                    'properties' => [
                        'theme' => [
                            'type' => 'string',
                            'enum' => ['light', 'dark', 'system'],
                        ],
                        'fontSize' => [
                            'type' => 'integer',
                            'minimum' => 10,
                            'maximum' => 24,
                        ],
                    ],
                ],
            ],
        ];
    }
}
