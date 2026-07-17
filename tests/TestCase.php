<?php

namespace Amol\LaravelJsonSafe\Tests;

use Amol\LaravelJsonSafe\LaravelJsonSafeServiceProvider;
use Amol\LaravelJsonSafe\Tests\Models\User;
use Orchestra\Testbench\TestCase as Orchestra;

use function Orchestra\Testbench\default_migration_path;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelJsonSafeServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('auth.providers.users.model', User::class);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(
            default_migration_path()
        );

        $this->loadMigrationsFrom(
            __DIR__.'/database/migrations'
        );

        $this->loadFactoriesUsing(app(), __DIR__.'/database/factories');
    }
}
