<?php

namespace JPeters\Architect\Http\Controllers;

use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Blueprints\BlueprintFormExtractor;
use JPeters\Architect\Blueprints\BlueprintListExtractor;

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

    public function add($blueprint) {
        /** @var Blueprint $concreteBlueprint */
        $concreteBlueprint = $this->architect->blueprintManager->resolve($blueprint);

        abort_if($concreteBlueprint === false, 404, 'Blueprint not found');

        return $this->architect->responseFactory->json(
            (new BlueprintFormExtractor($concreteBlueprint))->make()
        );
    }
}