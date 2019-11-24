<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Blueprints\BlueprintFormExtractor;
use JPeters\Architect\Blueprints\BlueprintListExtractor;
use JPeters\Architect\Controls\Control;
use JPeters\Architect\Http\Requests\BlueprintSubmitRequest;

class BlueprintController extends BaseController
{
    public function list($blueprint)
    {
        /** @var Blueprint $concreteBlueprint */
        $concreteBlueprint = $this->architect->blueprintManager->resolve($blueprint);

        abort_if($concreteBlueprint === false, 404, 'Blueprint not found');

        return $this->architect->responseFactory->json(
            (new BlueprintListExtractor($concreteBlueprint))->make()
        );
    }

    public function add($blueprint)
    {
        /** @var Blueprint $concreteBlueprint */
        $concreteBlueprint = $this->architect->blueprintManager->resolve($blueprint);

        abort_if($concreteBlueprint === false, 404, 'Blueprint not found');

        return $this->architect->responseFactory->json(
            (new BlueprintFormExtractor($concreteBlueprint))->make()
        );
    }

    public function submit(BlueprintSubmitRequest $request)
    {
        /** @var Blueprint $concreteBlueprint */
        $concreteBlueprint = $this->architect->blueprintManager->resolve($request->input('_blueprint'));

        abort_if($concreteBlueprint === false, 404, 'Blueprint not found');

        $modelClass = $concreteBlueprint->model();

        try {
            DB::beginTransaction();

            /** @var Model $model */
            $model = new $modelClass;
            $deferredPlans = [];

            (new Collection($concreteBlueprint->plans()))->each(static function (Control $plan) use ($model, $request, &$deferredPlans) {
                if (!$plan->isAvailableOnForm()) {
                    return;
                }

                if ($plan->deferUpdate) {
                    $deferredPlans[] = $plan;
                    return;
                }

                $plan->handleUpdate($model, $plan->getColumn(), $request->input($plan->getColumn()));
            });

            $model->save();

            foreach ($deferredPlans as $plan) {
                /** @var Control $plan */
                $plan->handleUpdate($model, $plan->getColumn(), $request->input($plan->getColumn()));
            }

            DB::commit();

            $url = config('app.url') . '/' . $concreteBlueprint->url() . '/' . $model->{$concreteBlueprint->slugField()};

            return new Response([
                'id' => $model->id,
                'blueprint' => $concreteBlueprint->blueprintName(),
                'url' => $model->{$concreteBlueprint->isVisibleField()} ? $url : null,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}