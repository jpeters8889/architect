<?php

namespace {{app-namespace}}\Architect\Pages\{{package-name-namespace}};

use JPeters\Architect\Architect;
use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Architect::isRunning(function () {
            /** @var Architect $architect */
            $architect = resolve(Architect::class);

            $architect->apiManager->registerEndpoint('post', '{{package-namespace-dash}}', ApiHandler::class);
            $architect->assetManager->registerScript('{{package-name-namespace}}', __DIR__.'/../dist/page.js');
            $architect->assetManager->registerStyle('{{package-name-namespace}}', __DIR__.'/../dist/page.css');
        });
    }
}
