<?php

namespace JPeters\Architect\Plans;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Model;

class Password extends InternalPlan
{
    protected $showOnIndex = false;

    public function handleUpdate(Model $model, $column, $value)
    {
        if ($value === '') {
            return;
        }

        parent::handleUpdate($model, $column, resolve(Hasher::class)->make($value));
    }

    public function getCurrentValue(Model $model)
    {
        return '';
    }

    public function vuePrefix()
    {
        return 'password';
    }
}
