<?php

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;
use JPeters\Architect\Traits\PlanHasLookupAction;

class Select extends InternalPlan
{
    use PlanHasLookupAction {
        getMetas as protected getMetasFromTrait;
    }

    /** @var array */
    private $options;

    private $showDefault = true;

    private $fromPivot = false;

    private $pivotColumn = null;

    private $multiSelect = false;

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
            'multiSelect' => $this->multiSelect,
        ]);
    }

    public function getCurrentValue(Model $model)
    {
        if(!$this->fromPivot) {
            return parent::getCurrentValue($model);
        }

        $bits = explode('_', $this->column);
        array_pop($bits);

        $value = $model;

        foreach ($bits as $bit) {
            $value = $value->$bit;
        }

        $value = $value->{$this->relationship};

        return $value->pluck('pivot.'.$this->pivotColumn);
    }

    public function findFromPivot($column) {
        $this->pivotColumn = $column;
        $this->fromPivot = true;

        return $this;
    }

    public function enableMultiselect()
    {
        $this->multiSelect = true;

        return $this;
    }
}
