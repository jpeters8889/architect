<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

use Closure;
use Illuminate\Database\Eloquent\Model;

class Button extends Plan
{
    private Closure $onClickHandler;

    private string $clickMethod = 'ajax';

    public function hasDatabaseColumn(): bool
    {
        return false;
    }

    public function vuePrefix(): string
    {
        return 'button';
    }

    public function getMetas(): array
    {
        return array_merge(parent::getMetas() ?? [], [
            'click_method' => $this->clickMethod,
        ]);
    }

    public function handleUpdate(Model $model, $column = null, $value = null)
    {
        return call_user_func($this->onClickHandler, $model);
    }

    public function onClick(Closure $closure): self
    {
        $this->onClickHandler = $closure;

        return $this;
    }

    public function openAsLink(): self
    {
        $this->clickMethod = 'link';

        return $this;
    }
}
