<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;

class Lookup extends PlanWithLookupAction
{
    public function vuePrefix(): string
    {
        return 'lookup';
    }

    public function getCurrentValue(Model $model)
    {
        $currentValue = parent::getCurrentValue($model);

        if (property_exists($this, 'getValueFrom') && $this->getValueFrom !== null) {
            return call_user_func($this->getValueFrom, $currentValue);
        }

        return $currentValue;
    }
}
