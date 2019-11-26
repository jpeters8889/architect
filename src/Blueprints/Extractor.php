<?php

namespace JPeters\Architect\Blueprints;

abstract class Extractor
{
    /**
     * @var Blueprint
     */
    protected $blueprint;

    public function __construct(Blueprint $blueprint)
    {
        $this->blueprint = $blueprint;
    }

    abstract public function make(): array;
}
