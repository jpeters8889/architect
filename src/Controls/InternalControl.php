<?php

namespace JPeters\Architect\Controls;

use Illuminate\Database\Eloquent\Model;

abstract class InternalControl extends Control
{
    public function handleUpdate(Model $model, $column, $value)
    {
        $model->$column = $value;
    }
}
