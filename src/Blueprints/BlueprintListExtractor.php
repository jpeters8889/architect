<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Database\Eloquent\Model;
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

        /** @var Model $model */
        $model = $this->blueprint->model();

        $this->columns[] = $this->blueprint->primaryField();

        return [
            'vue-suffix' => 'list',
            'labels' => $this->labels,
            'vuePrefixes' => $this->vuePrefix,
            'hiddenOnMobile' => $this->hideOnMobile,
            'data' => $model::query()->orderBy(...$this->blueprint->ordering())
                ->paginate(25, $this->columns),
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
                $this->columns[] = $column;

                if ($plan->isHiddenOnMobile()) {
                    $this->hideOnMobile[] = $column;
                }
            });
    }
}
