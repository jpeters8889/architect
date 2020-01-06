<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Response;
use JPeters\Architect\Http\Requests\ButtonRequest;
use Throwable;

class ButtonController extends BaseController
{
    public function handle(ButtonRequest $request)
    {
        try {
            return $this->architect->button->handleClickForButton(
                ...array_values($request->only(['button', 'blueprint', 'id']))
            );
        } catch (Throwable $e) {
            return new Response("Couldn't perform button click action", 400);
        }
    }
}
