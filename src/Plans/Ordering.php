<?php

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;

class Ordering extends InternalPlan
{
    public function vuePrefix()
    {
        return 'ordering';
    }
}
