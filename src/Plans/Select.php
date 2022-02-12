<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Select extends PlanWithLookupAction
{
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
        return array_merge(parent::getMetas(), [
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

    public function handleUpdate(Model $model, $column, $value, $index = null)
    {
        if (!$this->multiSelect) {
            $value = [$value];
        }

        if ($this->multiSelect && !is_array($value)) {
            $value = explode(',', $value);
        }

        if ($this->fromPivot) {
            /** @var BelongsToMany $relationship */
            $relationship = $model->{$this->relationship}();

            $relationship->detach();

            foreach ($value as $val) {
                $relationship->attach($val);
            }

            return;
        }

        foreach ($value as $val) {
            parent::handleUpdate($model, $column, $val);
        }
    }
}
