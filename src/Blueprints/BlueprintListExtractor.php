<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Support\Collection;
use JPeters\Architect\Plans\Plan;

class BlueprintListExtractor extends Extractor
{
    protected $labels = [];
    protected $vuePrefix = [];
    protected $columns = [];
    protected $hideOnMobile = [];

    public function make(): array
    {
        $this->processPlans();

        $this->columns[] = $this->blueprint->primaryField();

        $data = $this->blueprint->getData();
        $ordering = $this->blueprint->ordering();

        if (! is_array($ordering[0])) {
            $ordering = [$ordering];
        }
        foreach ($ordering as $order) {
            $data->orderBy(...$order);
        }

        return [
            'vue-suffix' => 'list',
            'labels' => $this->labels,
            'vuePrefixes' => $this->vuePrefix,
            'hiddenOnMobile' => $this->hideOnMobile,
            'data' => $data->paginate(25, $this->columns),
            'meta' => [
                'title' => $this->blueprint->blueprintName(),
            ],
        ];
    }

    private function processPlans()
    {
        (new Collection($this->blueprint->plans()))
            ->each(function (Plan $plan) {
                if (! $plan->isAvailableOnIndex()) {
                    return;
                }

                $column = $plan->getColumn();

                $this->labels[$column] = $plan->getLabel();
                $this->vuePrefix[$column] = $plan->vuePrefix();

                if ($plan->hasDatabaseColumn()) {
                    $this->columns[] = $column;
                }

                if ($plan->isHiddenOnMobile()) {
                    $this->hideOnMobile[] = $column;
                }
            });
    }
}
