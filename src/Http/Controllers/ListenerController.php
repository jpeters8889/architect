<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use Throwable;
use Illuminate\Http\Response;
use JPeters\Architect\Http\Requests\ListenerRequest;

class ListenerController extends BaseController
{
    public function __invoke(ListenerRequest $request)
    {
        try {
            return $this->architect->listener->executeListenerForPlan(
                ...array_values($request->only(['blueprint', 'event', 'column', 'value']))
            );
        } catch (Throwable $e) {
            dd($e);
            return new Response("Couldn't execute listener", 400);
        }
    }
}
