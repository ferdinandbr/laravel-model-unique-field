<?php

namespace Ferdinandbr\LaravelModelUniqueField\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Ferdinandbr\LaravelModelUniqueField\LaravelModelUniqueFieldServiceProvider;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
    }
    protected function getPackageProviders($app)
    {
        return [
            LaravelModelUniqueFieldServiceProvider::class,
        ];
    }
}
