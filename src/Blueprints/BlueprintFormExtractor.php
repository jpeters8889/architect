<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JPeters\Architect\Plans\Plan;

class BlueprintFormExtractor extends Extractor
{
    /** @var Model */
    private $currentModel;

    public function getValuesFrom($id)
    {
        $this->currentModel = $this->blueprint->model()::query()->findOrFail($id);

        return $this;
    }

    public function make(): array
    {
        $plans = [];

        (new Collection($this->blueprint->plans()))
            ->each(function (Plan $plan) use (&$plans) {
                if (! $plan->isAvailableOnForm()) {
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

    private function preparePlanArray(Plan $plan)
    {
        return [
            'label' => $plan->getLabel(),
            'name' => $plan->getColumn(),
            'component' => $plan->vuePrefix() . '-form',
            'value' => $this->currentModel ? $plan->getCurrentValue($this->currentModel) : $plan->getDefault(),
            'metas' => $plan->getMetas(),
        ];
    }
}
