<?php

declare(strict_types=1);

namespace JPeters\Architect\Blueprints;

use Illuminate\Http\Request;
use JPeters\Architect\Cards\Card;
use JPeters\Architect\Plans\Plan;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;

class BlueprintListExtractor extends Extractor
{
    protected array $labels = [];
    protected array $vuePrefix = [];
    protected array $columns = [];
    protected array $hideOnMobile = [];

    public function make(): array
    {
        $this->processPlans();

        $this->columns[] = $this->blueprint->primaryField();

        $data = $this->blueprint->getData();
        $ordering = $this->blueprint->ordering();

        if (!is_array($ordering[0])) {
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

        $data = $this->processSearch($data);
        $data = $this->processFilters($data);

        $data = $data->paginate($this->blueprint->perPage(), $this->columns);

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
            'searchable' => $this->blueprint->searchable(),
            'paginate' => $this->blueprint->paginate(),
            'filters' => $this->createFilters(),
            'meta' => [
                'title' => $this->blueprint->blueprintName(),
                'button' => $this->blueprint->button(),
            ],
        ];
    }

    private function createFilters(): Collection
    {
        return (new Collection($this->blueprint->filters()))
            ->mapWithKeys(static function ($filter, $identifier) {
                return [
                    $identifier => [
                        'name' => $filter['name'],
                        'options' => $filter['options'],
                        'multi' => $filter['multi'] ?? false,
                        'default' => $filter['default'] ?? null,
                    ],
                ];
            });
    }

    private function processPlans(): void
    {
        (new Collection($this->blueprint->plans()))
            ->each(function (Plan $plan) {
                if (!$plan->isAvailableOnIndex()) {
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

    protected function processSearch(Builder $data): Builder
    {
        /** @var Request $request */
        if (($request = Container::getInstance()->make(Request::class))->has('search')) {
            return $this->blueprint->searchUsing($data, $request->get('search'), $this->columns);
        }

        return $data;
    }

    protected function processFilters(Builder $data): Builder
    {
        if (count($filters = $this->blueprint->filters()) === 0) {
            return $data;
        }

        if (!($request = Container::getInstance()->make(Request::class))->has('filter')) {
            return $data;
        }

        foreach ($request->get('filter') as $key => $value) {
            if (array_key_exists($key, $filters)) {
                $closure = $filters[$key]['filter'];

                $data->where(static fn (Builder $builder) => $closure($builder, $value));
            }
        }

        return $data;
    }
}
