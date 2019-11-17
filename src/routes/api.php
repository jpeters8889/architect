<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/blueprints/{blueprint}')->group(static function () {
    Route::get('/list', 'BlueprintController@list');
    Route::get('/add', 'BlueprintController@add');
});

Route::post('/datasource', 'DatasourceController@list');
Route::post('/images', 'ImagesController@upload');