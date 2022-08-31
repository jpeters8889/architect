<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use JPeters\Architect\Http\Controllers\AssetController;
use JPeters\Architect\Http\Controllers\BlueprintController;
use JPeters\Architect\Http\Controllers\ButtonController;
use JPeters\Architect\Http\Controllers\ChartController as ChartControllerAlias;
use JPeters\Architect\Http\Controllers\DashboardController;
use JPeters\Architect\Http\Controllers\ExternalPlanController;
use JPeters\Architect\Http\Controllers\HealthController;
use JPeters\Architect\Http\Controllers\ListenerController;
use JPeters\Architect\Http\Controllers\LookupController;
use JPeters\Architect\Http\Controllers\OrderController;

/* @var Router $router */

$router->get('/health', HealthController::class);

$router->get('/assets/{asset}/{name}', AssetController::class);

$router->get('/dashboard/{dashboard}', DashboardController::class);
$router->get('/dashboard/{dashboard}/chart/{slug}', ChartControllerAlias::class);

$router->prefix('/blueprints')->group(function ($router) {
    $router->post('/submit', [BlueprintController::class, 'submit']);

    $router->prefix('/{blueprint}')->group(function ($router) {
        $router->get('/list', [BlueprintController::class, 'list']);
        $router->get('/add', [BlueprintController::class, 'form']);
        $router->get('/{id}', [BlueprintController::class, 'form']);
    });
});

$router->post('/button', ButtonController::class);

$router->post('/lookup', LookupController::class);
$router->post('/listener', ListenerController::class);
$router->post('/order', OrderController::class);

$router->any('/external/{route}/{method}/{id}', ExternalPlanController::class);
$router->any('/external/{route}/{method}', ExternalPlanController::class);
$router->any('/external/{route}', ExternalPlanController::class);
