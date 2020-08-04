<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;
use JPeters\Architect\Traits\PlanHasLookupAction;

class Select extends InternalPlan
{
    use PlanHasLookupAction {
        getMetas as protected getMetasFromTrait;
    }

    private array $options;

    private bool $showDefault = true;

    private bool $fromPivot = false;

    private ?string $pivotColumn = null;

    private bool $multiSelect = false;

    public function vuePrefix(): string
    {
        return 'select';
    }

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function hideDefault(): self
    {
        $this->showDefault = false;

        return $this;
    }

    public function getMetas(): array
    {
        return array_merge($this->getMetasFromTrait(), [
            'options' => $this->options ?? null,
            'showDefault' => $this->showDefault,
            'hasLookup' => isset($this->action) ? $this->action !== null : false,
            'multiSelect' => $this->multiSelect,
        ]);
    }

    public function getCurrentValue(Model $model)
    {
        if (!$this->fromPivot) {
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

    public function findFromPivot($column): self
    {
        $this->pivotColumn = $column;
        $this->fromPivot = true;

        return $this;
    }

    public function enableMultiselect(): self
    {
        $this->multiSelect = true;

        return $this;
    }
}
