<?php

namespace JPeters\Architect\Dashboards;

class DashboardGenerator
{
    protected $redirect = [];

    public function redirectTo($url, $asVue = true, $data = []): self
    {
        $this->redirect = [
            'url' => $url,
            'asVue' => $asVue,
            'data' => $data,
        ];

        return $this;
    }

    public function render(): array
    {
        return [
            'redirect' => $this->redirect,
        ];
    }
}
