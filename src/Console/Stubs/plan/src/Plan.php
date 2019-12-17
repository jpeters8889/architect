<?php

namespace {{app-namespace}}\Architect\Plans\{{package-name-namespace}};

use JPeters\Architect\Plans\Plan as ArchitectPlan;

class Plan extends ArchitectPlan
{
    public function vuePrefix()
    {
        return '{{package-name}}';
    }

    public function handleUpdate(Model $model, $column, $value)
    {
        $model->$column = $value;
    }
}
