<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('login screen can be rendered', function (): void {
    $response = $this->get('/cp/login');
    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function (): void {
    $user = User::factory()->create();

    $response = $this->post('/cp/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('admin.dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function (): void {
    $user = User::factory()->create();

    $this->post('/cp/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/cp/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
