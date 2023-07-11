<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;

class HiddenField extends InternalPlan
{
    public function vuePrefix(): string
    {
        return 'hidden-field';
    }

    public function handleUpdate(Model $model, $column, $value, $index = null)
    {
        return false;
    }

    public function getMetas(): array
    {
        return array_merge(parent::getMetas() ?? [], [
            'hide_label' => true,
        ]);
    }
}
