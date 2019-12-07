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
    protected $relationshipPivot = false;

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

        return $this;
    }

    public function setPivotRelationship($relationship)
    {
        $this->relationship = $relationship;
        $this->relationshipPivot = true;

        return $this;
    }

    public function getPlans()
    {
        return (new Collection($this->plans))
            ->transform(function (Plan $plan) {
                return [
                    'label' => $plan->getLabel(),
                    'name' => $plan->getColumn(),
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

    /**
     * @param Model $model
     * @param $column
     * @param $value
     *
     * @todo refactor
     */
    public function handleUpdate(Model $model, $column, $value)
    {
        $values = json_decode($value, true);

        $relationship = null;
        $pivots = [];

        if ($this->relationship && ! $this->relationshipPivot) {
            /** @var Relation $class */
            $class = $model->{$this->relationship}();
            /** @var Model $relationship */
            $relationship = $class->newModelInstance();
        }

        foreach ($this->plans as $plan) {
            /** @var Plan $plan */
            $column = $plan->getColumn();

            if (! $this->relationship) {
                parent::handleUpdate($model, $column, $values[$column]);
                continue;
            }

            if ($relationship && ! $this->relationshipPivot) {
                $relationship->$column = $values[$column];
                continue;
            }

            if (!empty($values[$column])) {
                $pivots[] = $column;
            }
        }

        if ($relationship && ! $this->relationshipPivot) {
            $model->{$this->relationship}()->save($relationship);
        }

        if (count($pivots) > 0) {
            $model->{$this->relationship}()->attach(array_filter($pivots));
        }
    }
}
