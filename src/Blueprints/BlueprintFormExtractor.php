<?php

declare(strict_types=1);

namespace JPeters\Architect\Blueprints;

use JPeters\Architect\Plans\Plan;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class BlueprintFormExtractor extends Extractor
{
    private Model $currentModel;

    public function getValuesFrom($id): self
    {
        $this->currentModel = $this->blueprint->model()::query()->findOrFail($id);

        return $this;
    }

    public function make(): array
    {
        $plans = [];

        (new Collection($this->blueprint->plans()))
            ->each(function (Plan $plan) use (&$plans) {
                if (!$plan->isAvailableOnForm()) {
                    return;
                }

                $plans[] = $this->preparePlanArray($plan);
            });

        return [
            'vue-suffix' => 'list',
            'plans' => $plans,
            'meta' => [
                'title' => $this->blueprint->blueprintName(),
            ],
        ];
    }

    private function preparePlanArray(Plan $plan): array
    {
        return [
            'label' => $plan->getLabel(),
            'name' => $plan->getColumn(),
            'component' => $plan->vuePrefix().'-form',
            'value' => isset($this->currentModel) ? $plan->getCurrentValue($this->currentModel) : $plan->getDefault(),
            'metas' => $plan->getMetas(),
        ];
    }
}
