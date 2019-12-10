<?php

namespace JPeters\Architect\Lookup;

use JPeters\Architect\Architect;
use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Plans\Lookup as LookupPlan;
use JPeters\Architect\Plans\Plan;
use RuntimeException;

class Lookup
{
    /** @var Architect */
    private $architect;

    /** @var Blueprint */
    private $blueprint;

    /** @var LookupPlan */
    private $plan;

    private $value;

    public function __construct(Architect $architect)
    {
        $this->architect = $architect;
    }

    public function performAction($blueprint, $column, $value)
    {
        $this->value = $value;

        $this->resolveBlueprint($blueprint);
        $this->resolvePlan($column);

        return $this->plan->performLookup($value);
    }

    private function resolveBlueprint($blueprint)
    {
        $this->blueprint = $this->architect->blueprintManager->resolve($blueprint);
    }

    private function resolvePlan($column)
    {
        foreach ($this->blueprint->plans() as $plan) {
            /** @var Plan $plan */
            if ($plan->getColumn() === $column) {
                $this->plan = $plan;
                return;
            }
        }

        throw new RuntimeException('Unable to resolve the plan');
    }
}
