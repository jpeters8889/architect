<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Request;

class ExternalFieldController extends BaseController
{
    public function handle(Request $request, $route)
    {
        return $this->architect->apiManager->loadEndpoint($request->method(), $route, $request);
    }
}
