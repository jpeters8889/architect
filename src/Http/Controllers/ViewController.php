<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Response;

class ViewController extends BaseController
{
    public function handle(): Response
    {
        try {
            return $this->architect->responseFactory->view('architect::view', [
                'blueprintManager' => $this->architect->blueprintManager,
                'assetManager' => $this->architect->assetManager,
            ]);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }
}
