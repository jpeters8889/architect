<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

class Boolean extends InternalPlan
{
    public function vuePrefix(): string
    {
        return 'bool';
    }
}
