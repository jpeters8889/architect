<?php

declare(strict_types=1);

namespace JPeters\Architect\Blueprints;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

abstract class Blueprint
{
    abstract public function model(): string;

    public function getData(): Builder
    {
        return $this->model()::query();
    }

    public function url(): string
    {
        return Str::lower(class_basename($this->model()));
    }

    public function primaryField(): string
    {
        return 'id';
    }

    public function isVisibleField(): string
    {
        return 'live';
    }

    public function slugField(): string
    {
        return 'slug';
    }

    public function ordering(): array
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

    public function makeVisible(): array
    {
        return [];
    }

    public function blueprintName(): string
    {
        return Str::plural(Str::title(class_basename($this->model())));
    }

    public function blueprintRoute(): string
    {
        return Str::slug(class_basename($this->model()));
    }

    public function blueprintSite(): string
    {
        return 'Main';
    }

    abstract public function plans(): array;

    public function card(): ?string
    {
        return null;
    }

    public function displayCount(): int
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
