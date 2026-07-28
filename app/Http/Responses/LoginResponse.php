<?php

namespace App\Http\Responses;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): Response
    {
        $user = $request->user();

        $user->syncRolesFromLegacyColumn();
        $user->ensureRolesHavePermissions();

        if ($user->hasRole(Role::CLIENTE)) {
            return $request->wantsJson()
                ? new JsonResponse(['two_factor' => false], 200)
                : redirect('/');
        }

        return $request->wantsJson()
            ? new JsonResponse(['two_factor' => false], 200)
            : redirect()->intended(Fortify::redirects('login'));
    }
}
