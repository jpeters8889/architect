<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Blueprints\SaveBlueprint;
use JPeters\Architect\Blueprints\BlueprintFormExtractor;
use JPeters\Architect\Blueprints\BlueprintListExtractor;
use JPeters\Architect\Http\Requests\BlueprintSubmitRequest;

class BlueprintController extends BaseController
{
    public function list(Request $request, string $blueprint): JsonResponse
    {
        /** @var Blueprint $concreteBlueprint */
        $concreteBlueprint = $this->architect->blueprintManager->resolve($blueprint);

        abort_if($concreteBlueprint === false, 404, 'Blueprint not found');

        return $this->architect->responseFactory->json(
            (new BlueprintListExtractor($concreteBlueprint))->make()
        );
    }

    public function form($blueprint, $id = null): JsonResponse
    {
        /** @var Blueprint $concreteBlueprint */
        $concreteBlueprint = $this->architect
            ->blueprintManager
            ->resolve($blueprint);

        abort_if($concreteBlueprint === false, 404, 'Blueprint not found');

        $blueprintFormExtractor = new BlueprintFormExtractor($concreteBlueprint);

        if ($id) {
            $blueprintFormExtractor->getValuesFrom($id);
        }

        return $this->architect->responseFactory->json(
            $blueprintFormExtractor->make()
        );
    }

    public function submit(BlueprintSubmitRequest $request): Response
    {
        /** @var Blueprint $concreteBlueprint */
        $concreteBlueprint = $this->architect
            ->blueprintManager
            ->resolve($request->input('_blueprint'));

        abort_if($concreteBlueprint === false, 404, 'Blueprint not found');

        return (new SaveBlueprint($concreteBlueprint, $request))->saveBlueprint();
    }
}
