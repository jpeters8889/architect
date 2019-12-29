<?php

use Illuminate\Support\Facades\Route;

Route::get('/assets/{asset}/{name}', 'AssetController@get');

Route::middleware('auth:api')->group(static function () {
    Route::prefix('/blueprints')->group(static function () {
        Route::post('/submit', 'BlueprintController@submit');

        Route::prefix('/{blueprint}')->group(static function () {
            Route::get('/list', 'BlueprintController@list');
            Route::get('/add', 'BlueprintController@form');
            Route::get('/{id}', 'BlueprintController@form');
        });
    });

    Route::post('/lookup', 'LookupController@handle');
    Route::post('/listener', 'ListenerController@handle');
    Route::post('/order', 'OrderController@handle');

    Route::any('/external/{route}/{method}', 'ExternalPlanController@handle');
});
