<?php

namespace JPeters\Architect\Plans;

use Closure;
use Illuminate\Database\Eloquent\Model;

class Label extends InternalPlan
{
    public function hasDatabaseColumn()
    {
        return false;
    }

    public function vuePrefix()
    {
        return 'label';
    }

    public function handleUpdate(Model $model, $column, $value)
    {
        return null;
    }
}
