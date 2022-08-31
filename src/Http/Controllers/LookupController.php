<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use Throwable;
use Illuminate\Http\Response;
use JPeters\Architect\Http\Requests\LookupRequest;

class LookupController extends BaseController
{
    public function __invoke(LookupRequest $request)
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
