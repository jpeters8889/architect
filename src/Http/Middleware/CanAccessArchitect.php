<?php

namespace JPeters\Architect\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use JPeters\Architect\ArchitectGateway;

class CanAccessArchitect
{
    public function handle(Request $request, Closure $next)
    {
        if(!$this->validateGateway()) {
            return new Response('', 403);
        }

        return $next($request);
    }

    /**
     * @return bool
     */
    protected function validateGateway(): bool
    {
        $class = config('architect.gateway');

        if (!$class) {
            return true;
        }

        /** @var ArchitectGateway $gateway */
        $gateway = new $class();

        return $gateway->canUseArchitect(Auth::user());
    }
}
