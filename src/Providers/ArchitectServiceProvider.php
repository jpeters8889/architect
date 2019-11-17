<?php

namespace JPeters\Architect\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use JPeters\Architect\Console\InstallCommand;
use JPeters\Architect\Console\PublishCommand;
use JPeters\Architect\Http\Middleware\ArchitectIsRunning;

class ArchitectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishCommands();
        }

        $this->registerViews();
        $this->registerRoutes();
    }

    public function register()
    {
        $this->commands([
            InstallCommand::class,
            PublishCommand::class,
        ]);
    }

    protected function registerPublishCommands()
    {
        $this->publishes([
            __DIR__ . '/../Console/Stubs/ArchitectServiceProvider.stub' => app_path('Providers/ArchitectServiceProvider.php')
        ], 'architect-provider');

        $this->publishes([
            __DIR__ . '/../../config/architect.php' => config_path('architect.php')
        ], 'architect-config');

        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/architect')
        ], 'architect-assets');
    }

    protected function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'architect');
    }

    protected function registerRoutes()
    {
        Route::group(
            [
                'namespace' => 'JPeters\Architect\Http\Controllers',
                'prefix' => config('architect.route').'/api',
                'middleware' => ['auth:api', ArchitectIsRunning::class],
            ],
            function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
            }
        );
    }
}