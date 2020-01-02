<?php

namespace JPeters\Architect\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use JPeters\Architect\Console\InstallCommand;
use JPeters\Architect\Console\MakePlanCommand;
use JPeters\Architect\Console\MakeCardCommand;
use JPeters\Architect\Console\PublishCommand;
use JPeters\Architect\Contracts\ImageUploaderContract;
use JPeters\Architect\Http\Middleware\ArchitectIsRunning;
use JPeters\Architect\Resources\ImageUploader;

class ArchitectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishCommands();
        }

        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../../config/architect.php', 'architect');
        }

        $this->registerInterfaces();
        $this->registerViews();
        $this->registerRoutes();

        if (! Str::hasMacro('explodeIntoCollection')) {
            Str::macro('explodeIntoCollection', static function ($value, $delimiter = ',') {
                return new Collection(explode($delimiter, $value));
            });
        }
    }

    public function register()
    {
        $this->commands([
            InstallCommand::class,
            MakeCardCommand::class,
            MakePlanCommand::class,
            PublishCommand::class,
        ]);
    }

    public function registerInterfaces()
    {
        $this->app->instance(ImageUploaderContract::class, new ImageUploader());
    }

    protected function registerPublishCommands()
    {
        $this->publishes([
            __DIR__ . '/../Console/Stubs/ArchitectServiceProvider.stub' => app_path('Providers/ArchitectServiceProvider.php'),
        ], 'architect-provider');

        $this->publishes([
            __DIR__ . '/../../config/architect.php' => config_path('architect.php'),
        ], 'architect-config');

        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/architect'),
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
                'prefix' => config('architect.route') . '/api',
                'middleware' => ArchitectIsRunning::class,
            ],
            function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
            }
        );
    }
}
