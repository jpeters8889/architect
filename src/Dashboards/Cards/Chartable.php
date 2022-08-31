<?php

namespace JPeters\Architect\Dashboards\Cards;

use Carbon\Carbon;

abstract class Chartable
{
    protected string $selectedDateRange;

    /** @var array{start: Carbon, end: Carbon, unit: string, format: string} */
    protected array $dateRange;

    public function __construct()
    {
        $this->computeDateRange();
    }

    protected function computeDateRange(): void
    {
        if (request()->has('range')) {
            if (request()->get('range') === 'custom') {
                $this->calculateCustomDateRange();

                return;
            }

            $this->dateRange = $this->dateRanges(request()->get('range'));
            $this->selectedDateRange = request()->get('range');

            return;
        }

        $this->dateRange = $this->dateRanges($this->defaultRange());
        $this->selectedDateRange = $this->defaultRange();
    }

    protected function calculateCustomDateRange()
    {
        $start = Carbon::parse(request()->get('from'));
        $end = Carbon::parse(request()->get('to'));

        $daysBetween = $start->diffInDays($end);

        $unit = 'day';
        $format = 'd/m';

        if($daysBetween > 180) {
            $unit = 'month';
            $format = 'm/y';
        }

        $this->selectedDateRange = 'custom';
        $this->dateRange = ['start' => $start->startOfDay(), 'end' => $end->startOfDay(), 'unit' => $unit, 'format' => $format];
    }

    protected function defaultRange(): string
    {
        return 'last7';
    }

    protected function dateRanges($range): array
    {
        $ranges = [
            'today' => ['start' => Carbon::now()->startOfDay(), 'end' => Carbon::now()->endOfDay(), 'unit' => 'hour', 'format' => 'ga'],
            'yesterday' => ['start' => Carbon::yesterday()->startOfDay(), 'end' => Carbon::yesterday()->endOfDay(), 'unit' => 'hour', 'format' => 'ga'],
            'thisWeek' => ['start' => Carbon::now()->startOf('week'), 'end' => Carbon::now()->endOfDay(), 'unit' => 'day', 'format' => 'd/m'],
            'thisMonth' => ['start' => Carbon::now()->startOf('month'), 'end' => Carbon::now()->endOfDay(), 'unit' => 'day', 'format' => 'd/m'],
            'thisYear' => ['start' => Carbon::now()->startOf('year'), 'end' => Carbon::now()->endOfDay(), 'unit' => 'month', 'format' => 'M'],
            'last7' => ['start' => Carbon::now()->subDays(7)->startOfDay(), 'end' => Carbon::now()->endOfDay(), 'unit' => 'day', 'format' => 'd/m'],
            'last14' => ['start' => Carbon::now()->subDays(14)->startOfDay(), 'end' => Carbon::now()->endOfDay(), 'unit' => 'day', 'format' => 'd/m'],
            'lastMonth' => ['start' => Carbon::now()->subMonth()->startOfDay(), 'end' => Carbon::now()->endOfDay(), 'unit' => 'day', 'format' => 'd/m'],
            'lastYear' => ['start' => Carbon::now()->subYear()->startOfMonth(), 'end' => Carbon::now()->endOfMonth(), 'unit' => 'month', 'format' => 'M y'],
        ];

        return $ranges[$range] ?? $ranges[$this->defaultRange()];
    }

    protected function chartType(): string
    {
        return 'line';
    }

    final public function draw(): array
    {
        return [
            'selectedDateRange' => $this->selectedDateRange,
            'chart' => [
                'type' => $this->chartType(),
                'data' => [
                    'labels' => $this->labels(),
                    'datasets' => $this->data(),
                ],
                'options' => $this->chartOptions(),
            ],
        ];
    }

    abstract protected function dataLabel(): string;

    protected function data(): array
    {
        return [[
            'label' => $this->dataLabel(),
            'data' => $this->calculateData(),
            'borderColor' => 'blue',
            'backgroundColor' => 'blue',
        ]];
    }

    protected function calculateData(): array
    {
        $unit = $this->dateRange['unit'];

        $data = [];

        $start = $this->dateRange['start']->clone();

        while ($start->lt($this->dateRange['end'])) {
            $data[] = $this->getData(
                $start->startOf($unit),
                $start->clone()->endOf($unit),
            );

            $start->add(1, $unit);
        }

        return $data;
    }

    protected function defaultOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ]
        ];
    }

    final protected function chartOptions(): array
    {
        return array_merge($this->defaultOptions(), $this->options());
    }

    protected function options(): array
    {
        return [];
    }

    abstract protected function getData(Carbon $start, Carbon $end): int|float|array;

    protected function labels(): array
    {
        $labels = [];

        $start = $this->dateRange['start']->clone();

        while ($start->lt($this->dateRange['end'])) {
            $labels[] = $start->format($this->dateRange['format']);
            $start->add(1, $this->dateRange['unit']);
        }

        return $labels;
    }
}
