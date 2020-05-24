<?php

namespace JPeters\Architect\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use JPeters\Architect\Architect;
use JPeters\Architect\ArchitectGateway;

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

    public function handle($request, Closure $next, ...$guards)
    {
        try {
            return parent::handle($request, $next, $guards);
        } catch (\Exception $exception) {
            return new Response('', 401);
        }
    }

}
