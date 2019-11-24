<?php

namespace JPeters\Architect\Controls;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use JPeters\Architect\Blueprints\Blueprint;

abstract class InternalControl extends Control
{
    public function handleUpdate(Model $model, $column, $value)
    {
        $model->$column = $value;
    }
}