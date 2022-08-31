<?php

declare(strict_types=1);

namespace JPeters\Architect\Dashboards\Cards;

use Illuminate\Support\Str;

class Chart extends Card
{
    protected string $type = 'chart';

    public function __construct(protected string $title, protected Chartable $chart)
    {
        //
    }

    public static function make(string $title, Chartable $chart): static
    {
        return new static($title, $chart);
    }

    public function draw(): array
    {
        return $this->chart->draw();
    }

    protected function renderContent()
    {
        return ['name' => $this->slug()];
    }
}
