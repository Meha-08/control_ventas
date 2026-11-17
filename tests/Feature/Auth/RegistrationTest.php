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

test('registration accepts eight letters without numbers or symbols', function () {
    $response = $this->post('/register', [
        'nombre_u' => 'Solo',
        'a_paterno' => 'Letras',
        'a_materno' => '',
        'email' => 'letras@example.com',
        'password' => 'abcdefgh',
        'password_confirmation' => 'abcdefgh',
    ]);

    $response->assertRedirect();
    assertAuthenticated();
});

test('registration rejects passwords shorter than eight characters', function () {
    $response = $this->post('/register', [
        'nombre_u' => 'Corto',
        'a_paterno' => 'User',
        'a_materno' => '',
        'email' => 'corto@example.com',
        'password' => 'abcdefg',
        'password_confirmation' => 'abcdefg',
    ]);

    $response->assertSessionHasErrors('password');
});
