<?php

use App\Models\User;

use function Pest\Laravel\assertAuthenticated;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertOk();
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'nombre_u' => 'Test',
        'a_paterno' => 'User',
        'a_materno' => '',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect();
    assertAuthenticated();

    $user = User::where('email', 'test@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->nombre_u)->toBe('Test');
    expect($user->a_paterno)->toBe('User');
    expect($user->hasRole('vendedor'))->toBeTrue();
});
