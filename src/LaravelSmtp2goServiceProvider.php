<?php

namespace RayNl\LaravelSmtp2go;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSmtp2goServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-smtp2go')
            ->hasConfigFile();
    }

    public function register()
    {
        parent::register();

        $this->app->bind('smtp2go-email', function ($app) {
            return new LaravelSmtp2goEmail();
        });

        return $this;
    }
}
