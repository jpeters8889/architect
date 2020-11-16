<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use JPeters\Architect\Traits\CheckArchitectGateway;
use JPeters\Architect\Http\Requests\ChangePasswordRequest;

class AuthController extends BaseController
{
    use AuthenticatesUsers;
    use CheckArchitectGateway;

    protected function authenticated(Request $request, $user): array
    {
        return [
            'data' => 'Logged In',
            'redirect' => $this->redirectPath($user),
        ];
    }

    protected function loggedOut(): array
    {
        return [
            'data' => 'Logged Out',
        ];
    }

    public function redirectPath($user): string
    {
        $path = config('architect.route');

        if (method_exists($user, 'architectSetting')) {
            $path = ltrim($path, '/').'/'.$user->architectSetting('landing-page');
        }

        return $path;
    }

    public function changePassword(ChangePasswordRequest $request, Hasher $hasher): void
    {
        $request->user()->update([
            'password' => $hasher->make($request->input('new_password')),
        ]);
    }
}
