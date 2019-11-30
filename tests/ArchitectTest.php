<?php

namespace JPeters\Architect\Tests;

use Illuminate\Foundation\Application;
use JPeters\Architect\Architect;
use JPeters\Architect\Providers\ArchitectApplicationServiceProvider;
use JPeters\Architect\Providers\ArchitectServiceProvider;
use JPeters\Architect\Tests\Models\User;
use JPeters\Architect\Tests\Providers\TestingServiceProvider;
use Orchestra\Testbench\TestCase;

class ArchitectTest extends TestCase
{
    /** @var Architect */
    protected $architect;

    /** @var Application */
    protected $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrations();

        $this->withFactories(__DIR__ . '/Factories');

        $this->architect = resolve(Architect::class);
    }

    protected function loadMigrations()
    {
        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--path' => realpath(__DIR__ . '/Migrations'),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            ArchitectServiceProvider::class,
            ArchitectApplicationServiceProvider::class,
            TestingServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->instance('path.public', __DIR__ . '/../public');

        $app['config']->set(['auth.providers.users.model' => User::class]);

        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
