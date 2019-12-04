<?php

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Group extends InternalPlan
{
    protected $plans;

    /**
     * @var Model
     */
    private $model;

    public function vuePrefix()
    {
        return 'group';
    }

    public function plans(array $plans)
    {
        $this->plans = $plans;
    }

    public function getCurrentValue(Model $model)
    {
        $this->model = $model;

        return null;
    }

    public function getPlans()
    {
        return (new Collection($this->plans))
            ->transform(function (Plan $plan) {
                return [
                    'label' => $plan->getLabel(),
                    'column' => $plan->getColumn(),
                    'component' => $plan->vuePrefix() . '-form',
                    'metas' => $plan->getMetas(),
                    'value' => $this->model ? $plan->getCurrentValue($this->model) : null,
                ];
            });
    }

    public function getMetas()
    {
        return array_merge(parent::getMetas() ?? [], [
            'plans' => $this->getPlans(),
        ]);
    }
}
