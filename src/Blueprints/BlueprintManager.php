<?php

declare(strict_types=1);

namespace JPeters\Architect\Blueprints;

use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Guard;

class BlueprintManager
{
    private array $blueprints = [];

    public function renderForNavigation(): array
    {
        if (!Container::getInstance()->make(Guard::class)) {
            return [];
        }

        $blueprints = new Collection($this->blueprints);

        $navigationSettings = [
            'buildings' => [],
            'blueprints' => [],
        ];

        $this->processBlueprintsForNavigation($blueprints, $navigationSettings);

        return $navigationSettings;
    }

    public function registerBlueprint($blueprint): void
    {
        $this->blueprints[] = $blueprint;
    }

    public function resolve(string $blueprintRoute)
    {
        foreach ($this->blueprints as $blueprint) {
            /** @var Blueprint $concrete */
            $concrete = new $blueprint();

            if ($concrete->blueprintRoute() === $blueprintRoute) {
                return $concrete;
            }
        }

        return false;
    }

    private function processBlueprintsForNavigation(Collection $blueprints, &$navigationSettings): void
    {
        $blueprints->each(static function ($blueprint) use (&$navigationSettings) {
            /** @var Blueprint $concreteBlueprint */
            $concreteBlueprint = new $blueprint();
            $blueprintSite = $concreteBlueprint->blueprintSite();

            if (!in_array($blueprintSite, $navigationSettings['buildings'], true)) {
                $navigationSettings['buildings'][] = $blueprintSite;
            }

            $navigationSettings['blueprints'][$blueprintSite][] = [
                'label' => $concreteBlueprint->blueprintName(),
                'route' => $concreteBlueprint->blueprintRoute(),
                'count' => $concreteBlueprint->displayCount(),
            ];
        });
    }

    public function blueprintList(): array
    {
        return $this->blueprints;
    }
}
