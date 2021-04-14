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
            $relatedValue = $this->getRelationshipInstance($model, $value);

            $value = $relatedValue->id;
        }

        $model->$column = $value;
    }

    /**
     * @return false|string[]
     */
    protected function getRelationPaths()
    {
        $bits = explode('_', $this->column);
        array_pop($bits);

        return $bits;
    }

    /**
     * @param Model $model
     *
     * @return Model|Relation
     */
    protected function getRelationshipModelInstance(Model $model)
    {
        /** @var Relation $relationship */
        $relationship = $model;

        foreach ($this->getRelationPaths() as $bit) {
            $relationship = $relationship->$bit();
        }

        return $relationship->newModelInstance();
    }

    /**
     * @param Model $model
     * @param $value
     *
     * @return mixed
     */
    protected function getRelationshipInstance(Model $model, $value)
    {
        $relationshipModel = $this->getRelationshipModelInstance($model);

        return $relationshipModel::query()->firstOrCreate([$this->relationship => $value]);
    }
}
