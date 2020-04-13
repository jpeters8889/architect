<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JPeters\Architect\Cards\Card;
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

        /** @var Builder $data */
        $data = $this->blueprint->getData();
        $ordering = $this->blueprint->ordering();

        if (! is_array($ordering[0])) {
            $ordering = [$ordering];
        }
        foreach ($ordering as $order) {
            $data->orderBy(...$order);
        }

        $cardClass = $this->blueprint->card();
        $card = null;

        if ($cardClass) {
            /** @var Card $concreteCard */
            $concreteCard = (new $cardClass());
            $this->columns = array_merge($this->columns, $concreteCard->modelParameters());
            $card = $concreteCard->make();
        }

        $data = $data->paginate(25, $this->columns);

        if ($this->blueprint->makeVisible() !== []) {
            $data->setCollection($data->getCollection()->makeVisible($this->blueprint->makeVisible()));
        }

        return [
            'vue-suffix' => 'list',
            'labels' => $this->labels,
            'card' => $card,
            'vuePrefixes' => $this->vuePrefix,
            'hiddenOnMobile' => $this->hideOnMobile,
            'data' => $data,
            'canEdit' => $this->blueprint->canEdit(),
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
