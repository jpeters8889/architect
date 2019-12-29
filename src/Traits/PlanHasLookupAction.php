<?php

namespace JPeters\Architect\Traits;

use Closure;

trait PlanHasLookupAction
{
    /** @var Closure */
    protected $action;

    protected $lookupVariable;

    protected $customValueAttribute;

    protected $getValueFrom;

    public function lookupAction(Closure $action)
    {
        $this->action = $action;

        return $this;
    }

    public function fetchValueFrom(Closure $closure)
    {
        $this->getValueFrom = $closure;

        return $this;
    }

    public function setLookupVariable($variable)
    {
        $this->lookupVariable = $variable;

        return $this;
    }

    public function setLookupAttribute($attribute)
    {
        $this->customValueAttribute = $attribute;

        return $this;
    }

    public function performLookup($value)
    {
        return call_user_func($this->action, $value);
    }

    public function getMetas()
    {
        return array_merge(parent::getMetas() ?? [], [
            'lookupVariable' => $this->lookupVariable,
            'customValueAttribute' => $this->customValueAttribute,
        ]);
    }
}
