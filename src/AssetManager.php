<?php

declare(strict_types=1);

namespace JPeters\Architect;

use RuntimeException;

class AssetManager
{
    protected array $scripts = [];
    protected array $styles = [];

    public function findAsset(string $asset, string $name): string
    {
        $repository = $this->scripts;

        if ($asset === 'style') {
            $repository = $this->styles;
        }

        if (!isset($repository[$name])) {
            throw new RuntimeException('Asset not found', 404);
        }

        return $repository[$name];
    }

    public function scripts(): array
    {
        return $this->scripts;
    }

    public function styles(): array
    {
        return $this->styles;
    }

    public function registerScript($name, $script): void
    {
        $this->scripts[$name] = $script;
    }

    public function registerStyle($name, $style): void
    {
        $this->styles[$name] = $style;
    }
}
