<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Request;

class ExternalPlanController extends BaseController
{
    public function handle(Request $request, $route, $method, $id = null)
    {
        if(!$method) {
            $method = 'handle';
        }

        $route .= "/{$method}";

        return $this->architect->apiManager->loadEndpoint($request->method(), $route, $request, $id);
    }
}
