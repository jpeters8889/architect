<?php

namespace JPeters\Architect\Blueprints;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JPeters\Architect\Controls\Control;
use JPeters\Architect\Http\Requests\BlueprintSubmitRequest;

class SaveBlueprint
{
    /** @var Blueprint */
    private $blueprint;

    /** @var BlueprintSubmitRequest */
    private $request;

    /** @var Model */
    private $model;

    private $deferredPlans = [];

    public function __construct(Blueprint $blueprint, BlueprintSubmitRequest $request)
    {
        $this->blueprint = $blueprint;
        $this->request = $request;

        $this->resolveModel();
    }

    protected function resolveModel()
    {
        $modelClass = $this->blueprint->model();

        $this->model = new $modelClass();

        if ($this->request->input('_state') === 'update') {
            $this->model = $modelClass::query()->find($this->request->input('_id'));
        }
    }

    public function saveBlueprint()
    {
        try {
            DB::beginTransaction();

            (new Collection($this->blueprint->plans()))
                ->each(function (Control $plan) {
                    $this->processControl($plan);
                });

            $this->model->save();

            $this->handleDeferredUpdates();

            DB::commit();

            return $this->returnResponse();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    protected function processControl(Control $plan)
    {
        if (! $plan->isAvailableOnForm()) {
            return;
        }

        if ($plan->deferUpdate) {
            $this->deferredPlans[] = $plan;
            return;
        }

        $plan->handleUpdate(
            $this->model,
            $plan->getColumn(),
            $this->request->input($plan->getColumn())
        );
    }

    protected function returnResponse()
    {
        $url = implode('/', [
            config('app.url'),
            $this->blueprint->url(),
            $this->model->{$this->blueprint->slugField()},
        ]);

        return new Response([
            'id' => $this->model->id,
            'blueprint' => $this->blueprint->blueprintName(),
            'url' => $this->model->{$this->blueprint->isVisibleField()} ? $url : null,
        ], 201);
    }

    protected function handleDeferredUpdates()
    {
        foreach ($this->deferredPlans as $plan) {
            /** @var Control $plan */
            $plan->handleUpdate(
                $this->model,
                $plan->getColumn(),
                $this->request->input($plan->getColumn())
            );
        }
    }
}
