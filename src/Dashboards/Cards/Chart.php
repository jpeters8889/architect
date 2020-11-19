<?php

namespace JPeters\Architect\Dashboards\Cards;

use Chartisan\PHP\Chartisan;
use Illuminate\Support\Str;

class Chart extends Card
{
    protected string $type = 'chart';

    protected array $labels = [];
    protected array $dataSets = [];
    private int $height = 500;
    protected array $types = [];

    protected function renderContent()
    {
        $chartisan = Chartisan::build();

        if ($this->labels) {
            $chartisan->labels($this->labels);
        }

        foreach ($this->dataSets as $data) {
            $chartisan->dataset(...$data);
        }

        return [
            'name' => Str::slug($this->title),
            'height' => $this->height,
            'data' => $chartisan->toObject(),
            'types' => $this->types,
        ];
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function setContent($content): self
    {
        return $this;
    }

    public function addLabels($labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    public function addDataSet(string $name, array $data, $type = 'bar'): self
    {
        $this->dataSets[] = [$name, $data];
        $this->types[] = $type;

        return $this;
    }
}
