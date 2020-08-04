<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

class DateTime extends InternalPlan
{
    public function vuePrefix(): string
    {
        return 'date-time';
    }
}
