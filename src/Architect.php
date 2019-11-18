<?php

namespace JPeters\Architect;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Event;
use JPeters\Architect\Blueprints\BlueprintManager;
use JPeters\Architect\DataSources\DataSourceManager;
use JPeters\Architect\Events\ArchitectRunning;

class Architect
{

    /** @var Guard */
    public $authGuard;

    /** @var ResponseFactory */
    public $responseFactory;

    /**
     * @var BlueprintManager
     */
    public $blueprintManager;

    /**
     * @var DataSourceManager
     */
    public $dataSourceManager;
    /**
     * @var AssetManager
     */
    public $assetManager;
    /**
     * @var ApiManager
     */
    public $apiManager;

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
        $this->dataSourceManager = new DataSourceManager();
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

    public function registerDataSource($key, $dataSource)
    {
        $this->dataSourceManager->registerDataSource($key, $dataSource);
    }

    public static function coreJavascript()
    {
        return [
            'prefix' => '/' . config('architect.route'),
            'apiRoot' => '/' . config('architect.route') . '/api',
        ];
    }
}