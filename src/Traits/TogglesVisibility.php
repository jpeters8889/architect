<?php

namespace JPeters\Architect\Traits;

trait TogglesVisibility
{
    protected $hideOnMobile = false;
    protected $showOnIndex = true;
    protected $showOnForm = true;

    public function hideFromIndexOnMobile()
    {
        $this->hideOnMobile = true;

        return $this;
    }

    public function isHiddenOnMobile()
    {
        return $this->hideOnMobile;
    }

    public function hideOnIndex()
    {
        $this->showOnIndex = false;

        return $this;
    }

    public function hideOnForms()
    {
        $this->showOnForm = false;

        return $this;
    }

    public function isAvailableOnIndex()
    {
        return $this->showOnIndex === true;
    }

    public function isAvailableOnForm()
    {
        return $this->showOnForm === true;
    }
}
