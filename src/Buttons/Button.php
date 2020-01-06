<?php

namespace JPeters\Architect\Buttons;

use JPeters\Architect\Architect;
use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Plans\Plan;
use RuntimeException;

class Button
{
    /** @var Architect */
    private $architect;

    /** @var Blueprint */
    private $blueprint;

    /** @var \JPeters\Architect\Plans\Button */
    private $plan;

    public function __construct(Architect $architect)
    {
        $this->architect = $architect;
    }

    public function handleClickForButton($button, $blueprint, $id)
    {
        $this->resolveBlueprint($blueprint);
        $this->resolvePlan($button);

        return $this->plan->handleUpdate($this->blueprint->model()::query()->findOrFail($id));
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
