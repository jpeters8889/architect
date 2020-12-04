<?php

namespace JPeters\Architect\Plans;

use Closure;
use Illuminate\Database\Eloquent\Model;

abstract class PlanWithLookupAction extends InternalPlan
{
    protected Closure $action;

    protected string $lookupVariable;

    protected string $customValueAttribute;

    protected $getValueFrom;

    protected ?Closure $createAction = null;

    public function lookupAction(Closure $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function fetchValueFrom(Closure $closure): self
    {
        $this->getValueFrom = $closure;

        return $this;
    }

    public function setLookupVariable(string $variable): self
    {
        $this->lookupVariable = $variable;

        return $this;
    }

    public function setLookupAttribute(string $attribute): self
    {
        $this->customValueAttribute = $attribute;

        return $this;
    }

    public function performLookup($value)
    {
        return call_user_func($this->action, $value);
    }

    public function getMetas(): array
    {
        return array_merge(parent::getMetas() ?? [], [
            'lookupVariable' => $this->lookupVariable ?? '',
            'customValueAttribute' => $this->customValueAttribute ?? '',
        ]);
    }

    protected function getRelationshipInstance(Model $model, $value)
    {
        if (!$this->createAction) {
            return parent::getRelationshipInstance($model, $value);
        }

        return call_user_func($this->createAction, $this->getRelationshipModelInstance($model), $value);
    }

    public function setCreateAction(Closure $action): self
    {
        $this->createAction = $action;

        return $this;
    }
}
