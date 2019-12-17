<?php

namespace JPeters\Architect\Plans;

use JPeters\Architect\Traits\PlanHasLookupAction;

class Lookup extends InternalPlan
{
    use PlanHasLookupAction;

    public function vuePrefix()
    {
        return 'lookup';
    }
}
