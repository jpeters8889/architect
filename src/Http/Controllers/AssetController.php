<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Response;

class AssetController extends BaseController
{
    public function get($asset, $name)
    {
        $contentType = 'application/javascript';

        if ($asset === 'style') {
            $contentType = 'text/css';
        }

        return new Response(
            file_get_contents($this->architect->assetManager->findAsset($asset, $name)),
            200,
            ['Content-Type' => $contentType]
        );
    }
}