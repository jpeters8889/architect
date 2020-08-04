<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Switcher extends Select
{
    protected array $optionPlans = [];

    private Model $model;

    public bool $deferUpdate = true;

    public function vuePrefix(): string
    {
        return 'switcher';
    }

    public function addPlansForOption($option, array $plans): self
    {
        $this->optionPlans[$option] = $plans;

        return $this;
    }

    public function getMetas(): array
    {
        return array_merge(parent::getMetas(), [
            'switches' => $this->getSwitches(),
        ]);
    }

    protected function getSwitches(): array
    {
        $switches = [];

        foreach ($this->optionPlans as $option => $plans) {
            $switches[$option] = $this->getPlans($plans);
        }

        return $switches;
    }

    protected function getPlans($plans): Collection
    {
        return (new Collection($plans))
            ->transform(function (Plan $plan) {
                return [
                    'label' => $plan->getLabel(),
                    'name' => $plan->getColumn(),
                    'component' => $plan->vuePrefix().'-form',
                    'value' => isset($this->model) ? $plan->getCurrentValue($this->model) : null,
                    'metas' => $plan->getMetas(),
                ];
            });
    }

    public function handleUpdate(Model $model, $column, $value)
    {
        $values = json_decode($value, true);

        $model->{$this->getColumn()} = $values[$this->getColumn()];

        foreach ($this->optionPlans[$values[$this->getColumn()]] as $plan) {
            /* @var Plan $plan */
            $plan->handleUpdate($model, $plan->getColumn(), $values[$plan->getColumn()]);
        }
    }

    public function getCurrentValue(Model $model)
    {
        $this->model = $model;

        return $model->{$this->getColumn()};
    }
}
