<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use JPeters\Architect\Dashboards\AbstractDashboard;
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

        $dashboard = $this->architect->dashboardManager->dashboardList()[0];

        /** @var AbstractDashboard $concreteDashboard */
        $concreteDashboard = new $dashboard();

        $page = 'dashboards/' . $concreteDashboard->dashboardRoute();

        if (method_exists($user, 'architectSetting')) {
            $page = $user->architectSetting('landing-page', $page);
        }

        return '/' . trim($path, '/') . '/' . $page;
    }

    public function changePassword(ChangePasswordRequest $request, Hasher $hasher): void
    {
        $request->user()->update([
            'password' => $hasher->make($request->input('new_password')),
        ]);
    }
}
