<?php

namespace JPeters\Architect\Controls;

class Textarea extends Control
{
    protected $rows = 3;

    public function vuePrefix()
    {
        return 'text-area';
    }

    public function rows($rows) {
        $this->rows = $rows;

        return $this;
    }

    public function getMetas()
    {
        return array_merge(parent::getMetas() ?? [], [
           'rows' => $this->rows,
        ]);
    }
}