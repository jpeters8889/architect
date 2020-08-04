<?php

namespace {{app-namespace}}\Architect\Cards\{{package-name-namespace}};

use JPeters\Architect\Architect;
use Illuminate\Support\ServiceProvider;

class CardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Architect::isRunning(function () {
            /** @var Architect $architect */
            $architect = Architect::getInstance();

            $architect->apiManager->registerEndpoint('post', '{{package-namespace-dash}}', ApiHandler::class);
            $architect->assetManager->registerScript('{{package-name-namespace}}', __DIR__.'/../dist/card.js');
            $architect->assetManager->registerStyle('{{package-name-namespace}}', __DIR__.'/../dist/card.css');
        });
    }
}
