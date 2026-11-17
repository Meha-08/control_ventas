<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (blank(config('services.google.client_id')) || blank(config('services.google.client_secret'))) {
            return to_route('login')->withErrors([
                'email' => 'Google no está configurado. Agrega GOOGLE_CLIENT_ID y GOOGLE_CLIENT_SECRET en el archivo .env y ejecuta: php artisan config:clear',
            ]);
        }

        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            report($e);

            return to_route('login')->withErrors([
                'email' => 'No se pudo iniciar sesión con Google. Inténtalo de nuevo.',
            ]);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user && $googleUser->getEmail()) {
            $user = User::where('email', $googleUser->getEmail())->first();
        }

        if (! $user) {
            $name = $googleUser->getName() ?: 'Usuario';
            $parts = preg_split('/\s+/', trim($name), 2);
            $user = User::create([
                'nombre_u' => $parts[0] ?? $name,
                'a_paterno' => $parts[1] ?? '',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Str::random(32),
                'email_verified_at' => now(),
                'active' => true,
                'role' => Role::CLIENTE,
            ]);
            $user->syncRoles([Role::CLIENTE]);
            $user->ensureRolesHavePermissions();
        } else {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        }

        if (! $user->active) {
            return to_route('login')->withErrors([
                'email' => 'Esta cuenta está desactivada. Contacta al administrador.',
            ]);
        }

        $this->ensureConfiguredAdmin($user);

        Auth::login($user, true);

        return redirect()->intended(url(config('fortify.home', '/dashboard')));
    }

    private function ensureConfiguredAdmin(User $user): void
    {
        $adminEmail = config('services.google.admin_email');

        if (! $adminEmail || ! hash_equals(strtolower($adminEmail), strtolower((string) $user->email))) {
            return;
        }

        $user->forceFill(['role' => Role::ADMIN, 'active' => true])->save();
        $user->syncRoles([Role::ADMIN]);
        $user->ensureRolesHavePermissions();
    }
}
