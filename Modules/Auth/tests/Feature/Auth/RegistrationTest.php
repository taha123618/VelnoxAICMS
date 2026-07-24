<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\Role;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('registration screen can be rendered', function (): void {
    $response = $this->get('/cp/register');

    $response->assertStatus(200);
});

test('new users can register', function (): void {
    Role::create(['name' => 'administrator', 'label' => 'Administrator']);

    $response = $this->post('/cp/register', [
        'first_name' => 'Taha',
        'last_name' => 'Ahmed',
        'email' => 'tahaahmedanees2@gmail.com',
        'password' => 'admin123',
        'password_confirmation' => 'admin123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('admin.dashboard', absolute: false));
});
