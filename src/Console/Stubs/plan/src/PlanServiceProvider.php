<?php

namespace {{app-namespace}}\Architect\Plans\{{package-name-namespace}};

use JPeters\Architect\Architect;
use Illuminate\Support\ServiceProvider;

class PlanServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Architect::isRunning(function () {
            /** @var Architect $architect */
            $architect = Architect::getInstance();

            $architect->apiManager->registerEndpoint('post', '{{package-namespace-dash}}', ApiHandler::class);
            $architect->assetManager->registerScript('{{package-name-namespace}}', __DIR__.'/../dist/plan.js');
            $architect->assetManager->registerStyle('{{package-name-namespace}}', __DIR__.'/../dist/plan.css');
        });
    }
}
