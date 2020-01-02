<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Request;

class ExternalPlanController extends BaseController
{
    public function handle(Request $request, $route, $method, $id = null)
    {
        return $this->architect->apiManager->loadEndpoint($request->method(), $route, $request, $id);
    }
}
