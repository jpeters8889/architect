<?php

namespace JPeters\Architect\Controls;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use JPeters\Architect\Traits\TogglesVisibility;

abstract class Control
{
    use TogglesVisibility;

    public $deferUpdate = false;

    protected $label;
    protected $column;

    private $metas;

    public function __construct($column, $label = null)
    {
        $this->column = $column;

        if (! $label) {
            $label = str_replace('_', ' ', Str::title($column));
        }

        $this->label = $label;
    }

    public function getCurrentValue(Model $model)
    {
        return $model->{$this->getColumn()};
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function withMetas($metas)
    {
        $this->metas = $metas;
    }

    public function getMetas()
    {
        return $this->metas;
    }

    abstract public function vuePrefix();

    abstract public function handleUpdate(Model $model, $column, $value);
}
