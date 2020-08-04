<?php

declare(strict_types=1);

namespace JPeters\Architect\Traits;

trait TogglesVisibility
{
    protected bool $hideOnMobile = false;
    protected bool $showOnIndex = true;
    protected bool $showOnForm = true;

    public function hideFromIndexOnMobile(): self
    {
        $this->hideOnMobile = true;

        return $this;
    }

    public function isHiddenOnMobile(): bool
    {
        return $this->hideOnMobile;
    }

    public function hideOnIndex(): self
    {
        $this->showOnIndex = false;

        return $this;
    }

    public function hideOnForms(): self
    {
        $this->showOnForm = false;

        return $this;
    }

    public function isAvailableOnIndex(): bool
    {
        return $this->showOnIndex === true;
    }

    public function isAvailableOnForm(): bool
    {
        return $this->showOnForm === true;
    }
}
