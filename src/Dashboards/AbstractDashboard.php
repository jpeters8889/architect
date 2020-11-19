<?php

namespace JPeters\Architect\Dashboards;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JPeters\Architect\Dashboards\Cards\Card;

abstract class AbstractDashboard
{
    abstract public function cards(): array;

    public function dashboardName(): string
    {
        return Str::title(class_basename($this));
    }

    public function dashboardRoute(): string
    {
        return Str::slug(class_basename($this));
    }

    public function render(): array
    {
        try {
            return [
                'title' => $this->dashboardName(),
                'cards' => (new Collection($this->cards()))
                    ->transform(fn(Card $card) => $card->render())
                    ->toArray(),
            ];
        }
        catch(\Exception $e) {
            dd($e);
        }
    }
}
