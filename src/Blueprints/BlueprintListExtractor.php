<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JPeters\Architect\Controls\Control;

class BlueprintListExtractor extends Extractor
{
    public function make(): array
    {
        $labels = [];
        $vuePrefix = [];
        $columns = [];
        $hideOnMobile = [];

        (new Collection($this->blueprint->plans()))
            ->each(static function (Control $plan) use (&$labels, &$vuePrefix, &$columns, &$hideOnMobile) {
                if (!$plan->isAvailableOnIndex()) {
                    return;
                }

                $column = $plan->getColumn();

                $labels[$column] = $plan->getLabel();
                $vuePrefix[$column] = $plan->vuePrefix();
                $columns[] = $column;

                if ($plan->isHiddenOnMobile()) {
                    $hideOnMobile[] = $column;
                }
            });

        /** @var Model $model */
        $model = $this->blueprint->model();

        $columns[] = $this->blueprint->primaryField();

        return [
            'vue-suffix' => 'list',
            'labels' => $labels,
            'vuePrefixes' => $vuePrefix,
            'hiddenOnMobile' => $hideOnMobile,
            'data' => $model::query()->orderBy(...$this->blueprint->ordering())->paginate(25, $columns),
            'meta' => [
                'title' => $this->blueprint->blueprintName(),
            ]
        ];
    }
}