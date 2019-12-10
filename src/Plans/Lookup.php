<?php

namespace JPeters\Architect\Plans;

use Closure;

class Lookup extends InternalPlan
{
    /**
     * @var Closure
     */
    private $action;

    public function vuePrefix()
    {
        return 'lookup';
    }

    public function lookupAction(Closure $action)
    {
        $this->action = $action;

        return $this;
    }

    public function performLookup($value)
    {
        return call_user_func($this->action, $value);
    }
}
