<?php

namespace Amol\LaravelJsonSafe;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelJsonSafeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-jsonsafe')
            ->hasConfigFile();
    }
}
