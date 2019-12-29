<?php

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Switcher extends Select
{
    protected $optionPlans = [];

    /** @var Model */
    private $model;

    public $deferUpdate = true;

    public function vuePrefix()
    {
        return 'switcher';
    }

    public function addPlansForOption($option, array $plans)
    {
        $this->optionPlans[$option] = $plans;

        return $this;
    }

    public function getMetas()
    {
        return array_merge(parent::getMetas(), [
            'switches' => $this->getSwitches(),
        ]);
    }

    protected function getSwitches()
    {
        $switches = [];

        foreach ($this->optionPlans as $option => $plans) {
            $switches[$option] = $this->getPlans($plans);
        }

        return $switches;
    }

    protected function getPlans($plans)
    {
        return (new Collection($plans))
            ->transform(function (Plan $plan) {
                return [
                    'label' => $plan->getLabel(),
                    'name' => $plan->getColumn(),
                    'component' => $plan->vuePrefix() . '-form',
                    'value' => $this->model ? $plan->getCurrentValue($this->model) : null,
                    'metas' => $plan->getMetas(),
                ];
            });
    }

    public function handleUpdate(Model $model, $column, $value)
    {
        $values = json_decode($value, true);

        $model->{$this->getColumn()} = $values[$this->getColumn()];

        foreach ($this->optionPlans[$values[$this->getColumn()]] as $plan) {
            /** @var Plan $plan */
            $plan->handleUpdate($model, $plan->getColumn(), $values[$plan->getColumn()]);
        }
    }

    public function getCurrentValue(Model $model)
    {
        $this->model = $model;

        return $model->{$this->getColumn()};
    }
}
