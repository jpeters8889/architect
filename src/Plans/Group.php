<?php

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use JPeters\Architect\AnonymousModel;

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

    private function getCurrentValueFromPivot($model)
    {
        $parameters = [];

        $model->{$this->relationship}->each(static function ($related) use (&$parameters) {
            $parameters[$related->id] = '1';
        });

        $this->model = new AnonymousModel($parameters);
    }

    public function getCurrentValue(Model $model)
    {
        if ($this->relationshipPivot) {
            $this->getCurrentValueFromPivot($model);

            return;
        }

        if ($this->relationship) {
            $this->model = $model->{$this->relationship}()->getModels()[0];

            return;
        }

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

    public function handleDefaultUpdate(Model $model, array $values)
    {
        foreach ($this->plans as $plan) {
            /** @var Plan $plan */
            parent::handleUpdate($model, $plan->getColumn(), $values[$plan->getColumn()]);
        }
    }

    public function handleRelationshipUpdate(Model $model, array $values)
    {
        /** @var Relation $class */
        $class = $model->{$this->relationship}();
        /** @var Model $relationship */
        $relationship = $class->newModelInstance();

        foreach ($this->plans as $plan) {
            /** @var Plan $plan */
            $column = $plan->getColumn();
            $relationship->$column = $values[$column];
        }

        $model->{$this->relationship}()->save($relationship);
    }

    public function handlePivotRelationshipUpdate(Model $model, array $values)
    {
        $pivots = [];

        foreach ($this->plans as $plan) {
            /** @var Plan $plan */
            $column = $plan->getColumn();

            if (! empty($values[$column])) {
                $pivots[] = $column;
            }
        }

        if (count($pivots) > 0) {
            $model->{$this->relationship}()->detach();
            $model->{$this->relationship}()->attach(array_filter($pivots));
        }
    }

    public function handleUpdate(Model $model, $column, $value)
    {
        $values = json_decode($value, true);

        if ($this->relationshipPivot) {
            return $this->handlePivotRelationshipUpdate($model, $values);
        }

        if ($this->relationship) {
            return $this->handleRelationshipUpdate($model, $values);
        }

        return $this->handleDefaultUpdate($model, $values);
    }
}
