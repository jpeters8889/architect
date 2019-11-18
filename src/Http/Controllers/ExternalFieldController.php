<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Request;

class ExternalFieldController extends BaseController
{
    public function handle(Request $request, $route)
    {
        $httpMethod = strtolower($request->method());

        return $this->architect->apiManager->loadEndpoint($httpMethod, $route, $request);
    }
}