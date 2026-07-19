<?php

use Amol\LaravelJsonSafe\JsonSafeable;
use Amol\LaravelJsonSafe\Tests\Models\User;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\Factories\UserFactory;

/*
|--------------------------------------------------------------------------
| Simple JSON — Access
|--------------------------------------------------------------------------
*/

it('returns JsonSafeable instance for extras', function () {
    $user = app(UserFactory::class)->create(['extras' => ['name' => 'John']]);

    expect($user->extras)->toBeInstanceOf(JsonSafeable::class);
});

it('can access simple json properties via array syntax', function () {
    $user = app(UserFactory::class)->create(['extras' => ['name' => 'John', 'age' => 30]]);

    expect($user->extras['name'])->toBe('John')
        ->and($user->extras['age'])->toBe(30);
});

it('throws error for missing key via array access', function () {
    $user = app(UserFactory::class)->create(['extras' => ['name' => 'John']]);

    $user->extras['nonexistent'];
})->throws(ErrorException::class, 'Undefined array key');

/*
|--------------------------------------------------------------------------
| Simple JSON — Update
|--------------------------------------------------------------------------
*/

it('can update a simple json property via array syntax and persist', function () {
    $user = app(UserFactory::class)->create(['extras' => ['name' => 'John']]);

    $user->extras['name'] = 'Jane';
    $user->save();

    $fresh = $user->fresh();

    expect($fresh->extras['name'])->toBe('Jane');
});

it('preserves other properties via array syntax when updating one key', function () {
    $user = app(UserFactory::class)->create(['extras' => ['name' => 'John', 'age' => 30]]);

    $user->extras['name'] = 'Jane';
    $user->save();

    $fresh = $user->fresh();

    expect($fresh->extras['name'])->toBe('Jane')
        ->and($fresh->extras['age'])->toBe(30);
});

/*
|--------------------------------------------------------------------------
| Nested JSON — Access
|--------------------------------------------------------------------------
*/

it('can access nested properties via array syntax', function () {
    $user = app(UserFactory::class)->create([
        'extras' => ['address' => ['city' => 'Mumbai', 'state' => 'MH']],
    ]);

    expect($user->extras['address']['city'])->toBe('Mumbai')
        ->and($user->extras['address']['state'])->toBe('MH');
});

/*
|--------------------------------------------------------------------------
| Nested JSON — Update
|--------------------------------------------------------------------------
*/

it('can update a nested json property and persist', function () {
    $user = app(UserFactory::class)->create([
        'extras' => ['address' => ['city' => 'Mumbai', 'state' => 'MH']],
    ]);

    $user->extras['address']['city'] = 'Pune';
    $user->save();

    $fresh = $user->fresh();

    expect($fresh->extras['address']['city'])->toBe('Pune');
});

it('preserves sibling nested properties when updating one', function () {
    $user = app(UserFactory::class)->create([
        'extras' => ['address' => ['city' => 'Mumbai', 'state' => 'MH']],
    ]);

    $address = $user->extras['address'];
    $address['city'] = 'Pune';
    $user->extras['address'] = $address;
    $user->save();

    $fresh = $user->fresh();

    expect($fresh->extras['address']['city'])->toBe('Pune')
        ->and($fresh->extras['address']['state'])->toBe('MH');
});

/*
|--------------------------------------------------------------------------
| Single Creation
|--------------------------------------------------------------------------
*/

it('creates user with simple json extras', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => uniqid().'@test.com',
        'password' => 'secret',
        'extras' => ['name' => 'John', 'age' => 30],
    ]);

    $fresh = $user->fresh();

    expect($fresh->extras)->toBeInstanceOf(JsonSafeable::class)
        ->and($fresh->extras['name'])->toBe('John')
        ->and($fresh->extras['age'])->toBe(30);
});

it('creates user with nested json extras', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => uniqid().'@test.com',
        'password' => 'secret',
        'extras' => [
            'address' => ['city' => 'Mumbai', 'state' => 'MH'],
            'tags' => ['admin', 'editor'],
        ],
    ]);

    $fresh = $user->fresh();

    expect($fresh->extras['address']['city'])->toBe('Mumbai')
        ->and($fresh->extras['tags'])->toBe(['admin', 'editor']);
});

/*
|--------------------------------------------------------------------------
| Single Updation
|--------------------------------------------------------------------------
*/

it('updates extras via direct property assignment', function () {
    $user = app(UserFactory::class)->create(['extras' => ['name' => 'John']]);

    $user->extras = ['name' => 'Jane', 'age' => 25];
    $user->save();

    $fresh = $user->fresh();

    expect($fresh->extras['name'])->toBe('Jane')
        ->and($fresh->extras['age'])->toBe(25);
});

it('updates extras via fill', function () {
    $user = app(UserFactory::class)->create(['extras' => ['name' => 'John']]);

    $user->fill(['extras' => ['name' => 'Jane', 'age' => 25]]);
    $user->save();

    $fresh = $user->fresh();

    expect($fresh->extras['name'])->toBe('Jane')
        ->and($fresh->extras['age'])->toBe(25);
});

it('sets extras to null', function () {
    $user = app(UserFactory::class)->create(['extras' => ['name' => 'John']]);

    $user->extras = null;
    $user->save();

    $fresh = $user->fresh();

    expect($fresh->extras)->toBeNull();
});

it('can access while column is null', function () {
    $user = app(UserFactory::class)->create();
    expect($user->extras)->toBeNull();
});

/*
|--------------------------------------------------------------------------
| Serialization — toArray / toJson
|--------------------------------------------------------------------------
*/

it('serializes extras to array via toArray', function () {
    $user = app(UserFactory::class)->create(['extras' => ['name' => 'John', 'age' => 30]]);

    $array = $user->toArray();

    expect($array['extras'])->toBe(['name' => 'John', 'age' => 30]);
});

it('serializes empty array extras via toArray', function () {
    $user = app(UserFactory::class)->create(['extras' => []]);

    $array = $user->toArray();

    expect($array['extras'])->toBe([]);
});

it('serializes null extras via toArray', function () {
    $user = app(UserFactory::class)->create(['extras' => null]);

    $array = $user->toArray();

    expect($array['extras'])->toBeNull();
});

it('serializes extras to json via toJson', function () {
    $user = app(UserFactory::class)->create(['extras' => ['name' => 'John']]);

    $json = $user->toJson();
    $decoded = json_decode($json, true);

    expect($decoded['extras'])->toBe(['name' => 'John']);
});

it('serializes nested extras via toArray', function () {
    $user = app(UserFactory::class)->create([
        'extras' => ['address' => ['city' => 'Mumbai', 'state' => 'MH']],
    ]);

    $array = $user->toArray();

    expect($array['extras'])->toBe(['address' => ['city' => 'Mumbai', 'state' => 'MH']]);
});

/*
|--------------------------------------------------------------------------
| Bulk Creation — Eloquent
|--------------------------------------------------------------------------
*/

it('bulk creates users with json extras via loop', function () {
    $users = collect(range(1, 5))->map(function ($i) {
        return User::create([
            'name' => "User {$i}",
            'email' => "user{$i}@test.com",
            'password' => 'secret',
            'extras' => ['index' => $i, 'label' => "Label {$i}"],
        ]);
    });

    $users->each(function ($user) {
        $fresh = $user->fresh();

        expect($fresh->extras)->toBeInstanceOf(JsonSafeable::class)
            ->and($fresh->extras['label'])->toBe("Label {$fresh->extras['index']}");
    });
});

/*
|--------------------------------------------------------------------------
| Bulk Creation — Raw DB
|--------------------------------------------------------------------------
*/

it('bulk inserts users with json via insert', function () {
    $rows = collect(range(1, 5))->map(fn ($i) => [
        'name' => "User {$i}",
        'email' => "bulk{$i}@test.com",
        'password' => 'secret',
        'extras' => json_encode(['index' => $i]),
        'created_at' => now(),
        'updated_at' => now(),
    ])->toArray();

    DB::table('users')->insert($rows);

    $users = DB::table('users')->whereIn('email', collect($rows)->pluck('email'))->get();

    expect($users)->toHaveCount(5);

    $users->each(function ($row) {
        $decoded = json_decode($row->extras, true);

        expect($decoded)->toBeArray()
            ->and($decoded['index'])->toBeInt();
    });
});

/*
|--------------------------------------------------------------------------
| Bulk Updation — Eloquent
|--------------------------------------------------------------------------
*/

it('bulk updates extras via each update', function () {
    $users = collect(range(1, 5))->map(function ($i) {
        return app(UserFactory::class)->create(['extras' => ['index' => $i, 'status' => 'old']]);
    });

    $users->each(function ($user) {
        $user->extras['status'] = 'updated';
        $user->save();
    });

    $users->each(function ($user) {
        $fresh = $user->fresh();

        expect($fresh->extras['status'])->toBe('updated')
            ->and($fresh->extras['index'])->toBe($user->fresh()->extras['index']);
    });
});

/*
|--------------------------------------------------------------------------
| Bulk Updation — Raw DB
|--------------------------------------------------------------------------
*/

it('bulk updates extras via query builder update', function () {
    $users = collect(range(1, 5))->map(function ($i) {
        return app(UserFactory::class)->create(['extras' => ['index' => $i, 'status' => 'old']]);
    });

    $ids = $users->pluck('id');

    DB::table('users')
        ->whereIn('id', $ids)
        ->update(['extras' => json_encode(['status' => 'bulk_updated'])]);

    $rows = DB::table('users')->whereIn('id', $ids)->get();

    $rows->each(function ($row) {
        $decoded = json_decode($row->extras, true);

        expect($decoded['status'])->toBe('bulk_updated');
    });
});
