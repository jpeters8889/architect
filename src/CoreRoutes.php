<?php

namespace JPeters\Architect;

use Illuminate\Support\Facades\Route;

class CoreRoutes
{
    public function registerAuthRoutes()
    {
        Route::namespace('JPeters\Architect\Http\Controllers')
            ->prefix(config('architect.route'))
            ->middleware('web')
            ->group(static function() {
                Route::get('/login', 'LoginController@showLoginForm')->name('login');

                Route::post('/login', 'LoginController@login');
            });

        return $this;
    }

    public function registerViewRoutes() {
        Route::namespace('JPeters\Architect\Http\Controllers')
            ->middleware(config('architect.middleware'))
            ->prefix(config('architect.route'))
            ->group(static function() {
               Route::get('/', 'ViewController@handle');
               Route::get('/{view}', 'ViewController@handle')
                   ->where('view', '.*');
            });
    }
}