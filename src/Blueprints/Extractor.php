<?php

declare(strict_types=1);

namespace JPeters\Architect\Blueprints;

abstract class Extractor
{
    protected Blueprint $blueprint;

    public function __construct(Blueprint $blueprint)
    {
        $this->blueprint = $blueprint;
    }

    abstract public function make(): array;
}
