<?php

namespace JPeters\Architect\Controls;

abstract class DataSourceControl extends Control
{
    protected $dataset;

    public function useDataset($dataset) {
        $this->dataset = $dataset;

        return $this;
    }

    public function getMetas()
    {
        return array_merge(parent::getMetas() ?? [], [
            'dataset' => $this->dataset,
        ]);
    }
}