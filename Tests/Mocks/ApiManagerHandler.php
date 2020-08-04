<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Mocks;

use Illuminate\Http\Request;
use Illuminate\View\Factory;

class ApiManagerHandler
{
    public function handle()
    {
        return 'Handled';
    }

    public function handleWithDependencies(Request $request, Factory $viewFactory)
    {
        return [
          $request,
          $viewFactory,
        ];
    }

    public function first()
    {
        return 'first';
    }

    public function second()
    {
        return 'second';
    }
}
