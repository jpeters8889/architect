<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use JPeters\Architect\Buttons\Button;
use JPeters\Architect\Http\Requests\ButtonRequest;

class ButtonController extends BaseController
{
    public function handle(ButtonRequest $request, Button $button)
    {
        return $button->handleClickForButton($request->input('button'), $request->input('blueprint'), $request->input('id'));
    }
}
