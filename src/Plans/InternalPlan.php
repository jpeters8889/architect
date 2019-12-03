<?php

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;

abstract class InternalPlan extends Plan
{
    public function handleUpdate(Model $model, $column, $value)
    {
        $model->$column = $value;
    }
}
