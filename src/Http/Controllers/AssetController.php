<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Response;

class AssetController extends BaseController
{
    public function __invoke(string $asset, string $name): Response
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
