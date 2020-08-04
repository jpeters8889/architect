<?php

declare(strict_types=1);

namespace JPeters\Architect;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Config\Repository;
use JPeters\Architect\Lookup\Lookup;
use JPeters\Architect\Buttons\Button;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Auth\Authenticatable;
use JPeters\Architect\Events\ArchitectRunning;
use JPeters\Architect\Plans\Listeners\Listener;
use Illuminate\Contracts\Routing\ResponseFactory;
use JPeters\Architect\Blueprints\BlueprintManager;
use JPeters\Architect\Dashboards\DashboardContract;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class Architect
{
    public ApiManager $apiManager;

    public AssetManager $assetManager;

    public Guard $authGuard;

    public ResponseFactory $responseFactory;

    public BlueprintManager $blueprintManager;

    public Button $button;

    public DashboardContract $dashboard;

    public Lookup $lookup;

    public Listener $listener;

    public function __construct()
    {
        $this->bootstrapAppDependencies();
    }

    private function bootstrapAppDependencies(): void
    {
        $this->apiManager = new ApiManager();
        $this->assetManager = new AssetManager();
        $this->authGuard = Container::getInstance()->make(Guard::class);
        $this->blueprintManager = new BlueprintManager();
        $this->button = new Button($this);
        $this->listener = new Listener($this);
        $this->lookup = new Lookup($this);
        $this->responseFactory = Container::getInstance()->make(ResponseFactory::class);
    }

    public function buildPathTo(string $path): string
    {
        $route = trim(Container::getInstance()->make(ConfigRepository::class)->get('architect.route'), '/');

        return "{$route}/{$path}";
    }

    public static function isRunning(Closure $callback): void
    {
        Container::getInstance()->make(Dispatcher::class)->listen(ArchitectRunning::class, $callback);
    }

    public function registerBlueprint($blueprint): void
    {
        $this->blueprintManager->registerBlueprint($blueprint);
    }

    public function registerDashboard(DashboardContract $dashboard): void
    {
        $this->dashboard = $dashboard;
    }

    public static function coreJavascript(BlueprintManager $manager): array
    {
        $config = Container::getInstance()->make(ConfigRepository::class);

        return [
            'domain' => $config->get('app.url'),
            'siteName' => $config->get('app.name'),
            'prefix' => '/'.$config->get('architect.route'),
            'apiRoot' => '/'.$config->get('architect.route').'/api',
            'navigation' => $manager->renderForNavigation(),
            'user' => Container::getInstance()->make(Authenticatable::class),
            'canChangePassword' => $config->get('architect.can_change_password', false),
        ];
    }

    public static function getInstance()
    {
        return Container::getInstance()->make(self::class);
    }
}
