<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Authenticatable;
use JPeters\Architect\Traits\CheckArchitectGateway;

class CanAccessArchitect
{
    use CheckArchitectGateway;

    public function handle(Request $request, Closure $next)
    {
        if (Container::getInstance()->make(Authenticatable::class) && !$this->validateGateway()) {
            return new Response('', 403);
        }

        return $next($request);
    }
}
