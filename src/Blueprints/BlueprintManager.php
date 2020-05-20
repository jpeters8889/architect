<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BlueprintManager
{
    private $blueprints = [];

    public function renderForNavigation()
    {
        if (!Auth::check()) {
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

    public function registerBlueprint($blueprint)
    {
        $this->blueprints[] = $blueprint;
    }

    public function resolve($blueprintRoute)
    {
        foreach ($this->blueprints as $blueprint) {
            /** @var Blueprint $concrete */
            $concrete = new $blueprint();

            if ($concrete->blueprintRoute() === $blueprintRoute) {
                return $concrete;
            }
        }

        dd('end');

        return false;
    }

    /**
     * @param Collection $blueprints
     * @param $navigationSettings
     */
    private function processBlueprintsForNavigation(Collection $blueprints, &$navigationSettings): void
    {
        $blueprints->each(static function ($blueprint) use (&$navigationSettings) {
            /** @var Blueprint $concreteBlueprint */
            $concreteBlueprint = new $blueprint();
            $blueprintSite = $concreteBlueprint->blueprintSite();

            if (! in_array($blueprintSite, $navigationSettings['buildings'], true)) {
                $navigationSettings['buildings'][] = $blueprintSite;
            }

            $navigationSettings['blueprints'][$blueprintSite][] = [
                'label' => $concreteBlueprint->blueprintName(),
                'route' => $concreteBlueprint->blueprintRoute(),
                'count' => $concreteBlueprint->displayCount(),
            ];
        });
    }

    public function blueprintList()
    {
        return $this->blueprints;
    }
}
