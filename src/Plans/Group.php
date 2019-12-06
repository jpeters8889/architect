<?php

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class Group extends InternalPlan
{
    protected $plans;

    protected $wrapPlans = false;
    protected $relationship;

    public $deferUpdate = true;

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

        return $this;
    }

    public function getCurrentValue(Model $model)
    {
        $this->model = $model;

        return null;
    }

    public function wrapPlans()
    {
        $this->wrapPlans = true;

        return $this;
    }

    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
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
            'wrap' => $this->wrapPlans,
        ]);
    }

    public function handleUpdate(Model $model, $column, $value)
    {
        $values = json_decode($value, true);

        $relationship = null;

        if ($this->relationship) {
            /** @var Relation $class */
            $class = $model->{$this->relationship}();
            /** @var Model $relationship */
            $relationship = $class->newModelInstance();
        }

        foreach ($this->plans as $plan) {
            /** @var Plan $plan */
            $column = $plan->getColumn();

            if (!$relationship) {
                parent::handleUpdate($model, $column, $values[$column]);
                continue;
            }

            $relationship->$column = $values[$column];
        }

        if ($relationship) {
            $model->{$this->relationship}()->save($relationship);
        }
    }
}
