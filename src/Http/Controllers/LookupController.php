<?php

namespace JPeters\Architect\Http\Controllers;

use Exception;
use Illuminate\Http\Response;
use JPeters\Architect\Http\Requests\LookupRequest;
use Throwable;

class LookupController extends BaseController
{
    public function handle(LookupRequest $request)
    {
        try {
            return $this->architect->lookup->performAction(
                ...array_values($request->only(['blueprint', 'column', 'value']))
            );
        } catch (Throwable $e) {
            return new Response("Couldn't perform lookup", 400);
        }
    }
}
