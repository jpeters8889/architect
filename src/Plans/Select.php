<?php

namespace JPeters\Architect\Plans;

class Select extends InternalPlan
{
    /**
     * @var array
     */
    private $options;

    public function vuePrefix()
    {
        return 'select';
    }

    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getMetas()
    {
        return array_merge(parent::getMetas() ?? [], [
            'options' => $this->options,
        ]);
    }
}
