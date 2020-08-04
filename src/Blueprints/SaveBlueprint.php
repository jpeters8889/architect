<?php

declare(strict_types=1);

namespace JPeters\Architect\Blueprints;

use Exception;
use Illuminate\Http\Response;
use JPeters\Architect\Plans\Plan;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Connection as DatabaseConnection;
use JPeters\Architect\Http\Requests\BlueprintSubmitRequest;

class SaveBlueprint
{
    private Blueprint $blueprint;

    private BlueprintSubmitRequest $request;

    private Model $model;

    private array $deferredPlans = [];

    public function __construct(Blueprint $blueprint, BlueprintSubmitRequest $request)
    {
        $this->blueprint = $blueprint;
        $this->request = $request;

        $this->resolveModel();
    }

    protected function resolveModel(): void
    {
        $modelClass = $this->blueprint->model();

        $this->model = new $modelClass();

        if ($this->request->input('_state') === 'update') {
            $this->model = $modelClass::query()->find($this->request->input('_id'));
        }
    }

    public function saveBlueprint(): Response
    {
        /** @var DatabaseConnection $databaseConnection */
        $databaseConnection = Container::getInstance()->make(DatabaseConnection::class);

        try {
            $databaseConnection->beginTransaction();

            (new Collection($this->blueprint->plans()))
                ->each(function (Plan $plan) {
                    $this->processPlan($plan);
                });

            $this->model->save();

            $this->handleDeferredUpdates();

            $this->model->save();

            $databaseConnection->commit();

            return $this->returnResponse();
        } catch (Exception $exception) {
            $databaseConnection->rollBack();
            throw $exception;
        }
    }

    protected function processPlan(Plan $plan): void
    {
        if (!$plan->isAvailableOnForm()) {
            return;
        }

        if ($plan->deferUpdate) {
            $this->deferredPlans[] = $plan;

            return;
        }

        $plan->handleUpdate(
            $this->model,
            $plan->getColumn(),
            call_user_func([$this->request, $plan->requestMethod()], $plan->getColumn())
        );
    }

    protected function returnResponse(): Response
    {
        return new Response([
            'id' => $this->model->id,
            'blueprint' => $this->blueprint->blueprintName(),
            'url' => $this->generateUrl(),
        ], 201);
    }

    protected function handleDeferredUpdates(): void
    {
        foreach ($this->deferredPlans as $plan) {
            /* @var Plan $plan */
            $plan->handleUpdate(
                $this->model,
                $plan->getColumn(),
                call_user_func([$this->request, $plan->requestMethod()], $plan->getColumn())
            );
        }
    }

    protected function generateUrl(): ?string
    {
        $url = null;

        if ($this->model->{$this->blueprint->isVisibleField()}) {
            $url = implode('/', [
                Container::getInstance()->make(Repository::class)->get('app.url'),
                $this->blueprint->url(),
                $this->model->{$this->blueprint->slugField()},
            ]);
        }

        return $url;
    }
}
