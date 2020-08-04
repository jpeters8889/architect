<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

class Textarea extends InternalPlan
{
    protected int $rows = 3;

    public function vuePrefix(): string
    {
        return 'text-area';
    }

    public function rows($rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    public function getMetas(): array
    {
        return array_merge(parent::getMetas() ?? [], [
            'rows' => $this->rows,
        ]);
    }
}
