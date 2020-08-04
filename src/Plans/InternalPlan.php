<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class InternalPlan extends Plan
{
    public function handleUpdate(Model $model, $column, $value)
    {
        if (isset($this->relationship)) {
            $bits = explode('_', $this->column);
            array_pop($bits);

            /** @var Relation $relationship */
            $relationship = $model;

            foreach ($bits as $bit) {
                $relationship = $relationship->$bit();
            }

            $relationshipModel = $relationship->newModelInstance();

            $relatedValue = $relationshipModel::query()->firstOrCreate([$this->relationship => $value]);

            $value = $relatedValue->id;
        }

        $model->$column = $value;
    }
}
