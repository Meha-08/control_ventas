<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

function fakeGoogleUser(array $overrides = []): SocialiteUser
{
    return (new SocialiteUser)->map(array_merge([
        'id' => 'google-123',
        'name' => 'Mehaline Ayalazeballos',
        'email' => 'ayalazeballosmehaline0@gmail.com',
        'avatar' => 'https://example.com/avatar.jpg',
    ], $overrides));
}

test('google redirect uri matches the canonical callback route', function () {
    $redirect = config('services.google.redirect');

    expect($redirect)->not->toBeNull()
        ->and(parse_url($redirect, PHP_URL_PATH))->toBe(parse_url(route('auth.google.callback'), PHP_URL_PATH));
});

test('google redirect route sends the user to google', function () {
    config([
        'services.google.client_id' => 'test-client-id',
        'services.google.client_secret' => 'test-client-secret',
    ]);

    Socialite::shouldReceive('driver->with->redirect')
        ->once()
        ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

    $this->get(route('auth.google.redirect'))
        ->assertRedirect('https://accounts.google.com/o/oauth2/auth');
});

test('google redirect shows a clear error when credentials are missing', function () {
    config([
        'services.google.client_id' => null,
        'services.google.client_secret' => null,
    ]);

    $this->from(route('login'))
        ->get(route('auth.google.redirect'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');
});

test('google callback failures are reported and shown to the user', function () {
    Log::spy();

    Socialite::shouldReceive('driver->user')
        ->once()
        ->andThrow(new RuntimeException('redirect_uri_mismatch'));

    $this->from(route('login'))
        ->get(route('auth.google.callback'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    Log::shouldHaveReceived('error');
});

test('the configured admin email is authenticated as admin through google', function () {
    config(['services.google.admin_email' => 'ayalazeballosmehaline0@gmail.com']);

    $user = User::factory()->create([
        'email' => 'ayalazeballosmehaline0@gmail.com',
        'google_id' => null,
    ]);
    $user->assignRole(Role::VENDEDOR);

    Socialite::shouldReceive('driver->user')
        ->once()
        ->andReturn(fakeGoogleUser());

    $this->get(route('auth.google.callback'))
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);

    $user->refresh();

    expect($user->google_id)->toBe('google-123')
        ->and($user->role)->toBe('admin')
        ->and($user->rolesNombres())->toBe([Role::ADMIN])
        ->and($user->hasRole(Role::VENDEDOR))->toBeFalse();
});

test('a new google user is created as a client', function () {
    config(['services.google.admin_email' => 'admin@example.com']);

    Socialite::shouldReceive('driver->user')
        ->once()
        ->andReturn(fakeGoogleUser([
            'id' => 'google-456',
            'email' => 'nuevo@example.com',
            'name' => 'Nuevo Cliente',
        ]));

    $this->get(route('auth.google.callback'))
        ->assertRedirect(route('dashboard'));

    $user = User::where('email', 'nuevo@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->google_id)->toBe('google-456')
        ->and($user->role)->toBe(Role::CLIENTE)
        ->and($user->rolesNombres())->toBe([Role::CLIENTE]);

    $this->assertAuthenticatedAs($user);
});
