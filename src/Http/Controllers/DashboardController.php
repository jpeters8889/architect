<?php

namespace JPeters\Architect\Http\Controllers;

class DashboardController extends BaseController
{
    public function get()
    {
        return $this->architect->dashboard->build()->render();
    }
}
