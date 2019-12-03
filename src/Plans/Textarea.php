<?php

namespace JPeters\Architect\Plans;

class Textarea extends InternalPlan
{
    protected $rows = 3;

    public function vuePrefix()
    {
        return 'text-area';
    }

    public function rows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    public function getMetas()
    {
        return array_merge(parent::getMetas() ?? [], [
            'rows' => $this->rows,
        ]);
    }
}
