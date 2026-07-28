<?php

namespace App\Http\Responses;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request): Response
    {
        $user = $request->user();

        if ($user->hasRole(Role::CLIENTE)) {
            return $request->wantsJson()
                ? new JsonResponse(['two_factor' => false], 201)
                : redirect('/');
        }

        return $request->wantsJson()
            ? new JsonResponse(['two_factor' => false], 201)
            : redirect()->intended(Fortify::redirects('register'));
    }
}
