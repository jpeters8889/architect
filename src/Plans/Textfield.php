<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

class Textfield extends InternalPlan
{
    public function vuePrefix(): string
    {
        return 'text-field';
    }
}
