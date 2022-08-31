<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Http\Requests\OrderRequest;

class OrderController extends BaseController
{
    public function __invoke(OrderRequest $request): void
    {
        /** @var Blueprint $blueprint */
        $blueprint = $this->architect->blueprintManager->resolve($request->input('blueprint'));

        /** @var Model $model */
        $model = $blueprint->model();

        if ($request->input('order') === 'up') {
            $model::query()
                ->where($request->input('name'), $request->input('value'))
                ->decrement($request->input('name'));

            $model::query()
                ->where($request->input('name'), $request->input('value') - 1)
                ->orderBy('updated_at')
                ->first()
                ->increment($request->input('name'));

            return;
        }

        $model::query()
            ->where($request->input('name'), $request->input('value'))
            ->increment($request->input('name'));

        $model::query()
            ->where($request->input('name'), $request->input('value') + 1)
            ->orderBy('updated_at')
            ->first()
            ->decrement($request->input('name'));
    }
}
