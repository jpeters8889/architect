<?php

declare(strict_types=1);

namespace JPeters\Architect\Lookup;

use RuntimeException;
use JPeters\Architect\Architect;
use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Plans\Lookup as LookupPlan;

class Lookup
{
    private Architect $architect;

    private Blueprint $blueprint;

    private LookupPlan $plan;

    public function __construct(Architect $architect)
    {
        $this->architect = $architect;
    }

    public function performAction(string $blueprint, string $column, string $value)
    {
        $this->resolveBlueprint($blueprint);
        $this->resolvePlan($column);

        return $this->plan->performLookup($value);
    }

    private function resolveBlueprint(string $blueprint): void
    {
        $this->blueprint = $this->architect->blueprintManager->resolve($blueprint);
    }

    private function resolvePlan(string $column): void
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
