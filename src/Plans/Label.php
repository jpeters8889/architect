<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;

class Label extends InternalPlan
{
    public function hasDatabaseColumn(): bool
    {
        return false;
    }

    public function vuePrefix(): string
    {
        return 'label';
    }

    public function handleUpdate(Model $model, $column, $value)
    {
        return null;
    }
}
