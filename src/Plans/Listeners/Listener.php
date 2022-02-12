<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans\Listeners;

use JPeters\Architect\Plans\BulkBlueprintVariants;
use RuntimeException;
use JPeters\Architect\Architect;
use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Blueprints\Blueprint;

class Listener
{
    private Architect $architect;

    private Blueprint $blueprint;

    private ?Plan $plan = null;

    public function __construct(Architect $architect)
    {
        $this->architect = $architect;
    }

    public function executeListenerForPlan(string $blueprint, string $event, string $listener, string $value)
    {
        $value = json_decode($value, true);

        $this->resolveBlueprint($blueprint);
        $this->resolvePlan($listener);

        return $this->plan->executeEvent($event, $value);
    }

    private function resolveBlueprint(string $blueprint): void
    {
        $this->blueprint = $this->architect->blueprintManager->resolve($blueprint);
    }

    private function resolvePlan(string $column): void
    {
        foreach ($this->blueprint->plans() as $plan) {
            /** @var Plan $plan */
            if ($plan instanceof BulkBlueprintVariants) {
                $plan->rawPlans()->each(function ( $bulkPlan) use ($column) {
                    if ($bulkPlan->getColumn() === $column) {
                        $this->plan = $bulkPlan;
                    }
                });
            }

            if ($this->plan) {
                return;
            }

            if ($plan->getColumn() === $column) {
                $this->plan = $plan;

                return;
            }
        }

        throw new RuntimeException('Unable to resolve the plan');
    }
}
