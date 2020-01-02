<?php

namespace JPeters\Architect\Blueprints;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Collection;

class BlueprintManager
{
    private $blueprints = [];

    public function renderForNavigation()
    {
        $blueprints = new Collection($this->blueprints);

        /** @var ResponseFactory $responseFactory */
        $responseFactory = resolve(ResponseFactory::class);

        $navigationSettings = [
            'buildings' => [],
            'blueprints' => [],
        ];

        $this->processBlueprintsForNavigation($blueprints, $navigationSettings);

        return $responseFactory
            ->view('architect::navigation', compact('navigationSettings'))
            ->content();
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
