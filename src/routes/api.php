<?php

use Illuminate\Support\Facades\Route;

Route::get('/assets/{asset}/{name}', 'AssetController@get');

Route::middleware('auth:api')->group(static function () {
    Route::prefix('/blueprints/{blueprint}')->group(static function () {
        Route::get('/list', 'BlueprintController@list');
        Route::get('/add', 'BlueprintController@add');
    });

    Route::any('/external/{route}/{method}', 'ExternalFieldController@handle');

    Route::post('/datasource', 'DatasourceController@list');
});