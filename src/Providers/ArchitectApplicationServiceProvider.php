<?php

namespace JPeters\Architect\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use JPeters\Architect\Architect;
use JPeters\Architect\CoreRoutes;
use JPeters\Architect\Http\Middleware\ArchitectIsRunning;

class ArchitectApplicationServiceProvider extends ServiceProvider
{
    /**
     * @var Architect
     */
    protected $architect;

    public function boot()
    {
        $this->architect = new Architect();

        Architect::isRunning(function () {
            $this->registerBlueprints();
        });

        $this->registerCoreRoutes();

        $this->app->instance(Architect::class, $this->architect);
    }

    protected function blueprints()
    {
        return [];
    }

    protected function registerCoreRoutes()
    {
        Route::post(config('architect.route') . '/api/auth', [
            'middleware' => ['web', ArchitectIsRunning::class],
            'uses' => 'JPeters\Architect\Http\Controllers\AuthController@login',
        ]);

        Route::post(config('architect.route') . '/api/logout', [
            'middleware' => ['web', ArchitectIsRunning::class],
            'uses' => 'JPeters\Architect\Http\Controllers\AuthController@logout',
        ]);

        Route::post(config('architect.route') . '/api/change-password', [
            'middleware' => ['web', ArchitectIsRunning::class],
            'uses' => 'JPeters\Architect\Http\Controllers\AuthController@changePassword',
        ]);

        Route::namespace('JPeters\Architect\Http\Controllers')
            ->middleware(config('architect.middleware'))
            ->prefix(config('architect.route'))
            ->group(static function () {
                Route::get('/', 'ViewController@handle');
                Route::get('/{view}', 'ViewController@handle')
                    ->where('view', '.*');
            });
    }

    private function registerBlueprints()
    {
        (new Collection($this->blueprints()))->each(function ($blueprint) {
            $this->architect->registerBlueprint($blueprint);
        });
    }
}
