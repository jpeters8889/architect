<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Request;

class ExternalPlanController extends BaseController
{
    public function handle(Request $request, string $route, string $method = 'handle', $id = null)
    {
        $route .= "/{$method}";

        return $this->architect->apiManager->loadEndpoint($request->method(), $route, $request, $id);
    }
}
