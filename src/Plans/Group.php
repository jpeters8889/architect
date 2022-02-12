<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

use Illuminate\Support\Collection;
use JPeters\Architect\AnonymousModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Group extends InternalPlan
{
    protected array $plans;

    protected bool $wrapPlans = false;
    protected ?string $relationship;
    protected bool $relationshipPivot = false;

    public bool $deferUpdate = true;

    private Model $model;

    public function vuePrefix(): string
    {
        return 'group';
    }

    public function plans(array $plans): self
    {
        $this->plans = $plans;

        return $this;
    }

    private function getCurrentValueFromPivot(Model $model): void
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

        if (isset($this->relationship)) {
            $this->model = $model->{$this->relationship}()->getModels()[0];

            return;
        }

        $this->model = $model;

        return null;
    }

    public function wrapPlans(): self
    {
        $this->wrapPlans = true;

        return $this;
    }

    public function setRelationship($relationship): self
    {
        $this->relationship = $relationship;

        return $this;
    }

    public function setPivotRelationship($relationship): self
    {
        $this->relationship = $relationship;
        $this->relationshipPivot = true;

        return $this;
    }

    public function getPlans(): Collection
    {
        return (new Collection($this->plans))
            ->transform(function (Plan $plan) {
                return [
                    'label' => $plan->getLabel(),
                    'name' => $plan->getColumn(),
                    'component' => $plan->vuePrefix().'-form',
                    'metas' => $plan->getMetas(),
                    'value' => isset($this->model) ? $plan->getCurrentValue($this->model) : $plan->getDefault(),
                ];
            });
    }

    public function getMetas(): array
    {
        return array_merge(parent::getMetas() ?? [], [
            'plans' => $this->getPlans(),
            'wrap' => $this->wrapPlans,
        ]);
    }

    public function handleDefaultUpdate(Model $model, array $values): void
    {
        foreach ($this->plans as $plan) {
            /* @var Plan $plan */
            parent::handleUpdate($model, $plan->getColumn(), $values[$plan->getColumn()]);
        }
    }

    public function handleRelationshipUpdate(Model $model, array $values): void
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

    public function handlePivotRelationshipUpdate(Model $model, array $values): void
    {
        $pivots = [];

        foreach ($this->plans as $plan) {
            /** @var Plan $plan */
            $column = $plan->getColumn();

            if (!empty($values[$column])) {
                $pivots[] = $column;
            }
        }

        if (count($pivots) > 0) {
            $model->{$this->relationship}()->detach();
            $model->{$this->relationship}()->attach(array_filter($pivots));
        }
    }

    public function handleUpdate(Model $model, $column, $value, $index = null)
    {
        $values = json_decode($value, true);

        if ($this->relationshipPivot) {
            return $this->handlePivotRelationshipUpdate($model, $values);
        }

        if (isset($this->relationship)) {
            return $this->handleRelationshipUpdate($model, $values);
        }

        return $this->handleDefaultUpdate($model, $values);
    }
}
