<?php

namespace JPeters\Architect\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JPeters\Architect\Architect;

class Authenticate extends Middleware
{
    /**
     * @param Request $request
     * @param array $guards
     * @throws AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        if (Str::contains($request->path(), 'login')) {
            return;
        }

        parent::authenticate($request, $guards);
    }

    /**
     * @param Request $request
     * @param array $guards
     * @return void|null
     */
    protected function unauthenticated($request, array $guards)
    {
        return null;
    }
}
