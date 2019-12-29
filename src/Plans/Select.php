<?php

namespace JPeters\Architect\Plans;

use JPeters\Architect\Traits\PlanHasLookupAction;

class Select extends InternalPlan
{
    use PlanHasLookupAction {
        getMetas as protected getMetasFromTrait;
    }

    /** @var array */
    private $options;

    private $showDefault = true;

    public function vuePrefix()
    {
        return 'select';
    }

    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function hideDefault()
    {
        $this->showDefault = false;

        return $this;
    }

    public function getMetas()
    {
        return array_merge($this->getMetasFromTrait(), [
            'options' => $this->options,
            'showDefault' => $this->showDefault,
            'hasLookup' => $this->action !== null,
        ]);
    }
}
