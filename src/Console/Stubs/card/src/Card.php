<?php

namespace {{app-namespace}}\Architect\Cards\{{package-name-namespace}};

use JPeters\Architect\Cards\Card as ArchitectCard;

class Card extends ArchitectCard
{
    public function vueComponent(): string
    {
        return '{{package-name}}-card';
    }

    public function modelParameters(): array
    {
        return [];
    }
}
