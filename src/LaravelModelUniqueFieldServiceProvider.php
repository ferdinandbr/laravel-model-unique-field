<?php

namespace Ferdinandbr\LaravelModelUniqueField;

use Illuminate\Support\ServiceProvider;

class LaravelModelUniqueFieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}
}
