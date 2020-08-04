<?php

declare(strict_types=1);

use Illuminate\Routing\Router;

/* @var Router $router */

$router->get('/health', 'HealthController@get');

$router->get('/assets/{asset}/{name}', 'AssetController@get');

$router->get('/dashboard', 'DashboardController@get');

$router->prefix('/blueprints')->group(static function ($router) {
    $router->post('/submit', 'BlueprintController@submit');

    $router->prefix('/{blueprint}')->group(static function ($router) {
        $router->get('/list', 'BlueprintController@list');
        $router->get('/add', 'BlueprintController@form');
        $router->get('/{id}', 'BlueprintController@form');
    });
});

$router->post('/button', 'ButtonController@handle');

$router->post('/lookup', 'LookupController@handle');
$router->post('/listener', 'ListenerController@handle');
$router->post('/order', 'OrderController@handle');

$router->any('/external/{route}/{method}/{id}', 'ExternalPlanController@handle');
$router->any('/external/{route}/{method}', 'ExternalPlanController@handle');
$router->any('/external/{route}', 'ExternalPlanController@handle');
