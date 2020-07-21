<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JPeters\Architect\Http\Requests\ChangePasswordRequest;
use JPeters\Architect\Traits\CheckArchitectGateway;

class AuthController extends BaseController
{
    use AuthenticatesUsers;
    use CheckArchitectGateway;

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

    public function changePassword(ChangePasswordRequest $request, Hasher $hasher)
    {
        $request->user()->update([
           'password' => $hasher->make($request->input('new_password')),
        ]);
    }
}
