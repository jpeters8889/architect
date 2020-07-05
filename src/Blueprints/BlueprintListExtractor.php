<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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
            ],
        ];
    }

    private function createFilters(): Collection
    {
        return (new Collection($this->blueprint->filters()))
            ->mapWithKeys(function ($filter, $identifier) {
                return [$identifier => [
                    'name' => $filter['name'],
                    'options' => $filter['options']
                ]];
            });
    }

    private function processPlans()
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

    /**
     * @param Builder $data
     * @return Builder
     */
    protected function processSearch(Builder $data): Builder
    {
        /** @var Request $request */
        if (($request = resolve(Request::class))->has('search')) {
            $search = $request->get('search');

            $data = $data->where(function (Builder $builder) use ($search) {
                $tableName = $builder->getModel()->getTable();

                foreach ($this->columns as $column) {
                    if ($builder->columnExists($column)) {
                        $builder->orWhere($tableName . "." . $column, 'LIKE', "%{$search}%");
                    }
                }
            });
        }
        return $data;
    }

    protected function processFilters(Builder $data): Builder
    {
        if (count($filters = $this->blueprint->filters()) === 0) {
            return $data;
        }

        if (!($request = resolve(Request::class))->has('filter')) {
            return $data;
        }

        foreach ($request->get('filter') as $key => $value) {
            if (array_key_exists($key, $filters)) {
                $closure = $filters[$key]['filter'];

                $data->where(fn(Builder $builder) => $closure($builder, $value));
            }
        }

        return $data;
    }
}
