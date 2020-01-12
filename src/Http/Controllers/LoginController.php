<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
    use AuthenticatesUsers;

    protected function authenticated(Request $request, $user)
    {
        return [
          'data' => 'Logged In',
        ];
    }

    protected function loggedOut(Request $request)
    {
        return [
            'data' => 'Logged Out',
        ];
    }

    public function redirectPath()
    {
        return config('architect.route');
    }
}
