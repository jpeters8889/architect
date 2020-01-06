<?php

namespace JPeters\Architect;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Event;
use JPeters\Architect\Blueprints\BlueprintManager;
use JPeters\Architect\Buttons\Button;
use JPeters\Architect\Events\ArchitectRunning;
use JPeters\Architect\Lookup\Lookup;
use JPeters\Architect\Plans\Listeners\Listener;

class Architect
{
    /** @var ApiManager */
    public $apiManager;

    /** @var AssetManager */
    public $assetManager;

    /** @var Guard */
    public $authGuard;

    /** @var ResponseFactory */
    public $responseFactory;

    /** @var BlueprintManager */
    public $blueprintManager;

    /** @var Button */
    public $button;

    /** @var Lookup */
    public $lookup;

    /** @var Listener */
    public $listener;

    public function __construct()
    {
        $this->bootstrapAppDependencies();
    }

    private function bootstrapAppDependencies()
    {
        $this->apiManager = new ApiManager();
        $this->assetManager = new AssetManager();
        $this->authGuard = resolve(Guard::class);
        $this->blueprintManager = new BlueprintManager();
        $this->button = new Button($this);
        $this->listener = new Listener($this);
        $this->lookup = new Lookup($this);
        $this->responseFactory = resolve(ResponseFactory::class);
    }

    public function buildPathTo($path)
    {
        $route = trim(config('architecture.route'), '/');

        return "{$route}/{$path}";
    }

    public static function isRunning($callback)
    {
        Event::listen(ArchitectRunning::class, $callback);
    }

    public function registerBlueprint($blueprint)
    {
        $this->blueprintManager->registerBlueprint($blueprint);
    }

    public static function coreJavascript()
    {
        return [
            'prefix' => '/' . config('architect.route'),
            'apiRoot' => '/' . config('architect.route') . '/api',
        ];
    }
}
