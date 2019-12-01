<?php

namespace JPeters\Architect;

use HttpException;
use RuntimeException;

class AssetManager
{
    protected $scripts = [];
    protected $styles = [];

    /**
     * @param $asset
     * @param $name
     * @return mixed
     */
    public function findAsset($asset, $name)
    {
        $repository = $this->scripts;

        if ($asset === 'style') {
            $repository = $this->styles;
        }

        if (! isset($repository[$name])) {
            throw new RuntimeException('Asset not found', 404);
        }

        return $repository[$name];
    }

    public function scripts()
    {
        return $this->scripts;
    }

    public function styles()
    {
        return $this->styles;
    }

    public function registerScript($name, $script)
    {
        $this->scripts[$name] = $script;
    }

    public function registerStyle($name, $style)
    {
        $this->styles[$name] = $style;
    }
}
