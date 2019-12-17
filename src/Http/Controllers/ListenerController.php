<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Response;
use JPeters\Architect\Http\Requests\ListenerRequest;
use Throwable;

class ListenerController extends BaseController
{
    public function handle(ListenerRequest $request)
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
