<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use JPeters\Architect\Cards\Card;

abstract class Blueprint
{
    abstract public function model(): string;

    public function getData(): Builder
    {
        return $this->model()::query();
    }

    public function url()
    {
        return Str::lower(class_basename($this->model()));
    }

    public function primaryField()
    {
        return 'id';
    }

    public function isVisibleField()
    {
        return 'live';
    }

    public function slugField()
    {
        return 'slug';
    }

    public function ordering()
    {
        return [
            'created_at',
            'desc',
        ];
    }

    public function searchable(): bool
    {
        return true;
    }

    public function filters(): array
    {
        return [];
    }

    public function paginate(): bool
    {
        return true;
    }

    public function makeVisible()
    {
        return [];
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

    public function card()
    {
        return null;
    }

    public function displayCount()
    {
        return 0;
    }

    public function canEdit(): bool
    {
        return true;
    }

    public function perPage(): int
    {
        return 25;
    }
}
