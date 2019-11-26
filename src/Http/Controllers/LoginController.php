<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends BaseController
{
    use AuthenticatesUsers;

    public function showLoginForm()
    {
        if ($this->architect->authGuard->check()) {
            return $this->architect->responseFactory
                ->redirectTo($this->architect->buildPathTo('/'));
        }

        return $this->architect->responseFactory->view('architect::auth.login')->content();
    }

    public function redirectPath()
    {
        return config('architect.route');
    }
}
