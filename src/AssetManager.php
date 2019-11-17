<?php

namespace JPeters\Architect;

class AssetManager
{
    protected $scripts = [];
    protected $styles = [];

    public function registerScript($name, $script)
    {
        $this->scripts[$name] = $script;
    }

    public function registerStyle($name, $style)
    {
        $this->styles[$name] = $style;
    }
}