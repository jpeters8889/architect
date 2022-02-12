<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Hashing\Hasher;

class Password extends InternalPlan
{
    protected bool $showOnIndex = false;

    public function handleUpdate(Model $model, $column, $value, $index = null)
    {
        if ($value === '') {
            return;
        }

        parent::handleUpdate($model, $column, Container::getInstance()->make(Hasher::class)->make($value));
    }

    public function getCurrentValue(Model $model)
    {
        return '';
    }

    public function vuePrefix(): string
    {
        return 'password';
    }
}
