<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/cp/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    \Modules\Auth\Models\Role::create(['name' => 'administrator', 'label' => 'Administrator']);
    
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
