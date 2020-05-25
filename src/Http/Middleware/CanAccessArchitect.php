<?php

namespace JPeters\Architect\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use JPeters\Architect\ArchitectGateway;
use JPeters\Architect\Traits\CheckArchitectGateway;

class CanAccessArchitect
{
    use CheckArchitectGateway;

    public function handle(Request $request, Closure $next)
    {
        if($request->wantsJson() && Auth::user() && !$this->validateGateway()) {
            return new Response('', 403);
        }

        return $next($request);
    }
}
