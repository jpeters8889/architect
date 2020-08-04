<?php

declare(strict_types=1);

namespace JPeters\Architect\Buttons;

use RuntimeException;
use JPeters\Architect\Architect;
use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Plans\Button as ButtonPlan;

class Button
{
    private Architect $architect;

    private Blueprint $blueprint;

    private ButtonPlan $plan;

    public function __construct(Architect $architect)
    {
        $this->architect = $architect;
    }

    public function handleClickForButton(string $button, string $blueprint, int $id)
    {
        $this->resolveBlueprint($blueprint);
        $this->resolvePlan($button);

        return $this->plan->handleUpdate($this->blueprint->model()::query()->findOrFail($id));
    }

    private function resolveBlueprint($blueprint): void
    {
        $this->blueprint = $this->architect->blueprintManager->resolve($blueprint);
    }

    private function resolvePlan($column): void
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
