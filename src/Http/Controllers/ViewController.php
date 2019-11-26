<?php

namespace JPeters\Architect\Http\Controllers;

class ViewController extends BaseController
{
    public function handle()
    {
        return $this->architect->responseFactory->view('architect::view', [
            'blueprintManager' => $this->architect->blueprintManager,
            'assetManager' => $this->architect->assetManager,
        ]);
    }
}
