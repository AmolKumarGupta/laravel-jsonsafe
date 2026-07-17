<?php

namespace Amol\LaravelJsonSafe\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $guarded = [];

    protected $casts = [
        'extras' => \Amol\LaravelJsonSafe\JsonSafe::class,
    ];
}
