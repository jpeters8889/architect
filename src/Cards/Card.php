<?php

namespace JPeters\Architect\Cards;

abstract class Card
{
    public function modelParameters(): array
    {
        return [];
    }

    abstract public function vueComponent(): string;

    public function make()
    {
        return [
            'labels' => $this->modelParameters(),
            'component' => $this->vueComponent(),
        ];
    }
}
