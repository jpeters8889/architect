<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Support\Collection;
use JPeters\Architect\Controls\Control;

class BlueprintFormExtractor extends Extractor
{
    public function make(): array
    {
        $fields = [];

        (new Collection($this->blueprint->plans()))
            ->each(static function (Control $plan) use (&$fields) {
                if (!$plan->isAvailableOnForm()) {
                    return;
                }

                $fields[] = [
                    'label' => $plan->getLabel(),
                    'name' => $plan->getColumn(),
                    'component' => $plan->vuePrefix() . '-form',
                    'metas' => $plan->getMetas(),
                ];
            });

        return [
            'vue-suffix' => 'list',
            'fields' => $fields,
            'meta' => [
                'title' => $this->blueprint->blueprintName(),
            ],
        ];
    }
}