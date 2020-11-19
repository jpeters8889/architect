<?php

declare(strict_types=1);

namespace JPeters\Architect\Providers;

use Illuminate\Routing\Router;
use JPeters\Architect\Architect;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use JPeters\Architect\Dashboards\Dashboard;
use JPeters\Architect\Http\Middleware\ArchitectIsRunning;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class ArchitectApplicationServiceProvider extends ServiceProvider
{
    protected Architect $architect;

    public function boot()
    {
        $this->architect = new Architect();

        Architect::isRunning(function () {
            $this->registerBlueprints();
            $this->registerDashboards();
        });

        $this->registerCoreRoutes();

        $this->app->instance(Architect::class, $this->architect);
    }

    protected function blueprints(): array
    {
        return [];
    }

    public function dashboards(): array
    {
        return [
            Dashboard::class,
        ];
    }

    protected function registerCoreRoutes(): void
    {
        $router = Container::getInstance()->make(Router::class);
        $config = Container::getInstance()->make(ConfigRepository::class);

        $router->post($config->get('architect.route') . '/api/auth', [
            'middleware' => ['web', ArchitectIsRunning::class],
            'uses' => 'JPeters\Architect\Http\Controllers\AuthController@login',
        ]);

        $router->post($config->get('architect.route') . '/api/logout', [
            'middleware' => ['web', ArchitectIsRunning::class],
            'uses' => 'JPeters\Architect\Http\Controllers\AuthController@logout',
        ]);

        $router->post($config->get('architect.route') . '/api/change-password', [
            'middleware' => ['web', ArchitectIsRunning::class],
            'uses' => 'JPeters\Architect\Http\Controllers\AuthController@changePassword',
        ]);

        $router->namespace('JPeters\Architect\Http\Controllers')
            ->middleware($config->get('architect.middleware'))
            ->prefix($config->get('architect.route'))
            ->group(static function ($router) {
                $router->get('/', 'ViewController@handle');
                $router->get('/{view}', 'ViewController@handle')
                    ->where('view', '.*');
            });
    }

    private function registerBlueprints(): void
    {
        (new Collection($this->blueprints()))->map(function ($blueprint) {
            $this->architect->registerBlueprint($blueprint);
        });
    }

    private function registerDashboards(): void
    {
        (new Collection($this->dashboards()))->map(function ($dashboard) {
            $this->architect->registerDashboard($dashboard);
        });
    }
}
