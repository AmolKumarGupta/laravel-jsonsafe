<?php

namespace Amol\LaravelJsonSafe\Tests\Models;

use Amol\LaravelJsonSafe\JsonSafe;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $guarded = [];

    protected $casts = [
        'extras' => JsonSafe::class,
    ];
}
