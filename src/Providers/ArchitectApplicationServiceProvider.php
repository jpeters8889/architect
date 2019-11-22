<?php

namespace JPeters\Architect\Providers;

use Illuminate\Support\Collection;
use JPeters\Architect\Architect;
use JPeters\Architect\CoreRoutes;
use Illuminate\Support\ServiceProvider;

class ArchitectApplicationServiceProvider extends ServiceProvider
{
    /**
     * @var Architect
     */
    protected $architect;

    public function boot()
    {
        $this->architect = new Architect();

        Architect::isRunning(function ()  {
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
        (new CoreRoutes())
            ->registerAuthRoutes()
            ->registerViewRoutes();
    }

    private function registerBlueprints()
    {
        (new Collection($this->blueprints()))->each(function($blueprint) {
            $this->architect->registerBlueprint($blueprint);
        });
    }
}
