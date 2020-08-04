<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

class Ordering extends InternalPlan
{
    public function vuePrefix(): string
    {
        return 'ordering';
    }
}
