<?php

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JPeters\Architect\AnonymousModel;

class BulkBlueprintVariants extends InternalPlan
{
    protected array $plans;
    protected bool $showOnIndex = false;

    protected string $addButtonLabel = 'Add Option';
    private Model $model;

    protected bool $hideIfEmpty = false;

    public function vuePrefix(): string
    {
        return 'bulk-variant-group';
    }

    public function plans(array $plans): self
    {
        $this->plans = $plans;

        return $this;
    }

    public function getMetas(): array
    {
        return array_merge(parent::getMetas() ?? [], [
            'plans' => $this->getPlans(),
            'addLabel' => $this->addButtonLabel,
            'hideIfEmpty' => $this->hideIfEmpty,
        ]);
    }

    public function setAddButtonLabel(string $label): self {
        $this->addButtonLabel = $label;

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

    public function rawPlans(): Collection
    {
        return new Collection($this->plans);
    }

    public function handleUpdate(Model $model, $column, $value, $index = null)
    {
        foreach ($this->plans as $plan) {
            /* @var Plan $plan */
            $plan->handleUpdate($model, $plan->getColumn(), $value[$plan->getColumn()], $index);
        }

        $model->save();
    }

    public function getCurrentValue(Model $model)
    {
        if (isset($this->relationship)) {
            $this->model = $model->{$this->relationship}()->getModels()[0];

            return;
        }

        $this->model = $model;

        return null;
    }

    public function shouldAutomaticallyCreateModels(): bool
    {
        return true;
    }

    public function shouldHideWhenEmpty(): static
    {
        $this->hideIfEmpty = true;

        return $this;
    }
}
