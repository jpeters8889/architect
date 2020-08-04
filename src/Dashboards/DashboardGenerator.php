<?php

declare(strict_types=1);

namespace JPeters\Architect\Dashboards;

class DashboardGenerator
{
    protected array $redirect = [];

    public function redirectTo(string $url, bool $asVue = true, array $data = []): self
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
