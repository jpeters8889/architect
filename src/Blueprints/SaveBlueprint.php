<?php

declare(strict_types=1);

namespace JPeters\Architect\Blueprints;

use Exception;
use Illuminate\Http\Response;
use JPeters\Architect\Plans\BulkBlueprintVariants;
use JPeters\Architect\Plans\Plan;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Connection as DatabaseConnection;
use JPeters\Architect\Http\Requests\BlueprintSubmitRequest;
use JPeters\Architect\TestHelpers\Laravel\Models\User;

class SaveBlueprint
{
    private Blueprint $blueprint;

    private BlueprintSubmitRequest $request;

    /** @var Collection<Model> */
    private Collection $models;

    private ?BulkBlueprintVariants $bulkPlan = null;
    private array $deferredPlans = [];

    public function __construct(Blueprint $blueprint, BlueprintSubmitRequest $request)
    {
        $this->blueprint = $blueprint;
        $this->request = $request;
        $this->models = new Collection();

        $this->resolveModel();
    }

    protected function resolveModel(): void
    {
        $modelClass = $this->blueprint->model();

        if ($this->request->input('_state') === 'update') {
            $this->models = new Collection();
            $this->models->push($modelClass::query()->find($this->request->input('_id')));

            return;
        }

        $hasBulkPlan = collect($this->blueprint->plans())->filter(fn(Plan $plan) => $plan instanceof BulkBlueprintVariants);

        if ($hasBulkPlan->count() > 1) {
            throw new Exception('Blueprints can only have one bulk variant plan');
        }

        if ($hasBulkPlan->count() === 0) {
            $this->models->push(new $modelClass);

            return;
        }

        $bulkPlan = $hasBulkPlan->first();

        $values = call_user_func([$this->request, $bulkPlan->requestMethod()], $bulkPlan->getColumn());

        if(is_string($values)) {
            $values = json_decode($values, true);
        }

        foreach ($values as $value) {
            $this->models->push(new $modelClass);
        }
    }

    public function saveBlueprint(): Response
    {
        /** @var DatabaseConnection $databaseConnection */
        $databaseConnection = Container::getInstance()->make(DatabaseConnection::class);

        try {
            $this->models->each(function (Model $model, $index) use ($databaseConnection) {
                $databaseConnection->beginTransaction();

                $this->deferredPlans = [];

                (new Collection($this->blueprint->plans()))
                    ->each(fn(Plan $plan) => $this->processPlan($plan, $model));

                $model = $this->handleBulkValues($model, $index);

                $model->save();

                $this->handleDeferredUpdates($model);

                $model->save();

                $databaseConnection->commit();

                // One last save to trigger any saved events outside of the database transaction
                $model->save();
            });

            return $this->returnResponse();
        } catch (Exception $exception) {
            $databaseConnection->rollBack();

            throw $exception;
        }
    }

    protected function processPlan(Plan $plan, Model $model): void
    {
        if (!$plan->isAvailableOnForm()) {
            return;
        }

        if ($plan instanceof BulkBlueprintVariants) {
            $this->bulkPlan = $plan;

            return;
        }

        if ($plan->deferUpdate) {
            $this->deferredPlans[] = $plan;

            return;
        }

        $plan->handleUpdate(
            $model,
            $plan->getColumn(),
            call_user_func([$this->request, $plan->requestMethod()], $plan->getColumn())
        );
    }

    protected function returnResponse(): Response
    {
        return new Response([
            'id' => $this->blueprint->canBeAddedInBulk() ? $this->models->pluck('id') : $this->models[0]->id,
            'blueprint' => $this->blueprint->blueprintName(),
            'url' => $this->generateUrl(),
        ], 201);
    }

    protected function handleBulkValues(Model $model, $index): Model
    {
        if (!$this->bulkPlan) {
            return $model;
        }

        $values = call_user_func([$this->request, $this->bulkPlan->requestMethod()], $this->bulkPlan->getColumn());

        if(is_string($values)) {
            $values = json_decode($values, true);
        }

        $this->bulkPlan->handleUpdate(
            $model,
            $this->bulkPlan->getColumn(),
            $values[$index],
            $index,
        );

        return $model;
    }

    protected function handleDeferredUpdates(Model $model): void
    {
        foreach ($this->deferredPlans as $plan) {
            /* @var Plan $plan */
            $plan->handleUpdate(
                $model,
                $plan->getColumn(),
                call_user_func([$this->request, $plan->requestMethod()], $plan->getColumn())
            );
        }
    }

    protected function generateUrl(): ?string
    {
        if ($this->blueprint->canBeAddedInBulk() || !$this->blueprint->hasPublicLink()) {
            return null;
        }

        $url = null;

        $model = $this->models->first();

        if ($model->{$this->blueprint->isVisibleField()}) {
            $url = implode('/', [
                Container::getInstance()->make(Repository::class)->get('app.url'),
                $this->blueprint->url(),
                $model->{$this->blueprint->slugField()},
            ]);
        }

        return $url;
    }
}
