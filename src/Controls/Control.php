<?php

namespace JPeters\Architect\Controls;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class Control
{
    protected $label;
    protected $column;
    protected $hideOnMobile = false;
    protected $showOnIndex = true;
    protected $showOnForm = true;

    private $metas;

    public function __construct($column, $label = null)
    {
        $this->column = $column;

        if (!$label) {
            $label = str_replace('_', ' ', Str::title($column));
        }

        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function hideFromIndexOnMobile()
    {
        $this->hideOnMobile = true;

        return $this;
    }

    public function isHiddenOnMobile()
    {
        return $this->hideOnMobile;
    }

    public function hideOnIndex()
    {
        $this->showOnIndex = false;

        return $this;
    }

    public function hideOnForms()
    {
        $this->showOnForm = false;

        return $this;
    }

    public function isAvailableOnIndex()
    {
        return $this->showOnIndex === true;
    }

    public function isAvailableOnForm()
    {
        return $this->showOnForm === true;
    }

    public function withMetas($metas) {
        $this->metas = $metas;
    }

    public function getMetas()
    {
        return $this->metas;
    }

    abstract public function vuePrefix();

    public function handleUpdate(Model $model, $column, $value)
    {
        $model->$column = $value;
    }
}