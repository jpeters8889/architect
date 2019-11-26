<?php

namespace JPeters\Architect;

class AssetManager
{
    protected $scripts = [];
    protected $styles = [];

    public function findAsset($asset, $name)
    {
        $repository = $this->scripts;

        if ($asset === 'style') {
            $repository = $this->styles;
        }

        if (! isset($repository[$name])) {
            abort(404, 'Asset not found');
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
