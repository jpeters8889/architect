<?php

declare(strict_types=1);

namespace JPeters\Architect;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Guard;
use JPeters\Architect\Lookup\Lookup;
use JPeters\Architect\Buttons\Button;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Auth\Authenticatable;
use JPeters\Architect\Events\ArchitectRunning;
use JPeters\Architect\Plans\Listeners\Listener;
use Illuminate\Contracts\Routing\ResponseFactory;
use JPeters\Architect\Blueprints\BlueprintManager;
use JPeters\Architect\Dashboards\DashboardManager;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class Architect
{
    public ApiManager $apiManager;

    public AssetManager $assetManager;

    public Guard $authGuard;

    public ResponseFactory $responseFactory;

    public BlueprintManager $blueprintManager;

    public Button $button;

    public DashboardManager $dashboardManager;

    public Lookup $lookup;

    public Listener $listener;

    protected static ?self $instance;

    public function __construct()
    {
        $this->bootstrapAppDependencies();

        self::$instance = $this;
    }

//    public static function getInstance()
//    {
//        return self::$instance;
//    }

    private function bootstrapAppDependencies(): void
    {
        $this->apiManager = new ApiManager();
        $this->assetManager = new AssetManager();
        $this->authGuard = Container::getInstance()->make(Guard::class);
        $this->blueprintManager = new BlueprintManager();
        $this->dashboardManager = new DashboardManager();
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

    public function registerDashboard($dashboard): void
    {
        $this->dashboardManager->registerDashboard($dashboard);
    }

    public static function coreJavascript(): array
    {
        $config = Container::getInstance()->make(ConfigRepository::class);

        return [
            'domain' => $config->get('app.url'),
            'siteName' => $config->get('app.name'),
            'prefix' => '/'.$config->get('architect.route'),
            'apiRoot' => '/'.$config->get('architect.route').'/api',
            'dashboards' => self::$instance->dashboardManager->renderForNavigation(),
            'navigation' => self::$instance->blueprintManager->renderForNavigation(),
            'user' => Container::getInstance()->make(Authenticatable::class),
            'canChangePassword' => $config->get('architect.can_change_password', false),
        ];
    }

    public static function getInstance()
    {
        return self::$instance;
    }
}
