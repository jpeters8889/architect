<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Support\Str;

abstract class Blueprint
{
    abstract public function model(): string;

    public function primaryField()
    {
        return 'id';
    }

    public function ordering()
    {
        return [
            'created_at',
            'desc',
        ];
    }

    public function blueprintName()
    {
        return Str::plural(Str::title(class_basename($this->model())));
    }

    public function blueprintRoute()
    {
        return Str::slug(class_basename($this->model()));
    }

    public function blueprintSite()
    {
        return 'Main';
    }

    abstract public function plans(): array;
}