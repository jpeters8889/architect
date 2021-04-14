<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Container\Container;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class Authenticate extends Middleware
{
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
        } catch (Exception $exception) {
            if ($request->wantsJson()) {
                return new Response('', 401);
            }

            return new RedirectResponse(
                Container::getInstance()->make(ConfigRepository::class)->get('architect.route').'/login'
            );
        }
    }
}
