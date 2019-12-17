<?php

namespace JPeters\Architect\Plans\Listeners;

use JPeters\Architect\Architect;
use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Plans\Lookup as LookupPlan;
use JPeters\Architect\Plans\Plan;
use RuntimeException;

class Listener
{

    /** @var Architect */
    private $architect;

    /** @var Blueprint */
    private $blueprint;

    /** @var Plan */
    private $plan;

    private $value;

    public function __construct(Architect $architect)
    {
        $this->architect = $architect;
    }

    public function executeListenerForPlan($blueprint, $event, $listener, $value)
    {
        $value = json_decode($value, true);

        $this->resolveBlueprint($blueprint);
        $this->resolvePlan($listener);

        return $this->plan->executeEvent($event, $value);
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
