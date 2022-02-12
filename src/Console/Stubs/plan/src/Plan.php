<?php

namespace {{app-namespace}}\Architect\Plans\{{package-name-namespace}};

use JPeters\Architect\Plans\Plan as ArchitectPlan;
use Illuminate\Database\Eloquent\Model;

class Plan extends ArchitectPlan
{
    public function vuePrefix(): string
    {
        return '{{package-name}}';
    }

    public function handleUpdate(Model $model, $column, $value, $index = null)
    {
        $model->$column = $value;
    }
}
