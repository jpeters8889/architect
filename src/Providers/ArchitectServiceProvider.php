<?php

declare(strict_types=1);

namespace JPeters\Architect\Providers;

use Illuminate\Support\Str;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use JPeters\Architect\Console\BuildCommand;
use JPeters\Architect\Console\InstallCommand;
use JPeters\Architect\Console\PublishCommand;
use JPeters\Architect\Console\MakeCardCommand;
use JPeters\Architect\Console\MakePageCommand;
use JPeters\Architect\Console\MakePlanCommand;
use JPeters\Architect\TestHelpers\Laravel\ArchitectGateway;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class ArchitectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishCommands();
        }

        if (!$this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../../config/architect.php', 'architect');
        }

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->registerViews();
        $this->registerRoutes();
        $this->registerMacros();
    }

    public function register()
    {
        $this->commands([
            BuildCommand::class,
            InstallCommand::class,
            MakeCardCommand::class,
            MakePageCommand::class,
            MakePlanCommand::class,
            PublishCommand::class,
        ]);

        if ($gateway = config('architect.gateway')) {
            $this->app->instance(ArchitectGateway::class, new $gateway());
        }
    }

    protected function registerPublishCommands(): void
    {
        $this->publishes([
            __DIR__.'/../Console/Stubs/ArchitectServiceProvider.stub' => app_path('Providers/ArchitectServiceProvider.php'),
        ], 'architect-provider');

        $this->publishes([
            __DIR__.'/../../config/architect.php' => config_path('architect.php'),
        ], 'architect-config');

        $this->publishes([
            __DIR__.'/../../public' => public_path('vendor/architect'),
        ], 'architect-assets');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'architect');
    }

    protected function registerRoutes(): void
    {
        $router = Container::getInstance()->make(Router::class);
        $config = Container::getInstance()->make(ConfigRepository::class);

        $router->group(
            [
                'namespace' => 'JPeters\Architect\Http\Controllers',
                'prefix' => $config->get('architect.route').'/api',
                'middleware' => $config->get('architect.middleware'),
            ],
            function ($router) {
                require __DIR__.'/../routes/api.php';
            }
        );
    }

    protected function registerMacros(): void
    {
        if (!Str::hasMacro('explodeIntoCollection')) {
            Str::macro('explodeIntoCollection', static function ($value, $delimiter = ',') {
                return new Collection(explode($delimiter, $value));
            });
        }

        if (!Builder::hasGlobalMacro('columnExists')) {
            Builder::macro('columnExists', function ($column) {
                $columns = $this->getConnection()->getDoctrineSchemaManager()->listTableColumns($this->getModel()->getTable());

                return array_key_exists($column, $columns);
            });
        }
    }
}
