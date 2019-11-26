<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JPeters\Architect\Controls\Control;

class BlueprintFormExtractor extends Extractor
{
    /** @var Model */
    private $currentModel;

    public function getValuesFrom($id)
    {
        $this->currentModel = $this->blueprint->model()::query()->findOrFail($id);
    }

    public function make(): array
    {
        $fields = [];

        (new Collection($this->blueprint->plans()))
            ->each(function (Control $plan) use (&$fields) {
                if (! $plan->isAvailableOnForm()) {
                    return;
                }

                $fields[] = $this->prepareFieldArray($plan);
            });

        return [
            'vue-suffix' => 'list',
            'fields' => $fields,
            'meta' => [
                'title' => $this->blueprint->blueprintName(),
            ],
        ];
    }

    private function prepareFieldArray(Control $plan)
    {
        return [
            'label' => $plan->getLabel(),
            'name' => $plan->getColumn(),
            'component' => $plan->vuePrefix() . '-form',
            'metas' => $plan->getMetas(),
            'value' => $this->currentModel ? $plan->getCurrentValue($this->currentModel) : null,
        ];
    }
}
