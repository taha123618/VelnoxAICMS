<?php

uses(Tests\TestCase::class);

use Modules\Auth\Models\User;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get('/cp/dashboard');
    $response->assertRedirect('/cp/login');
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/cp/dashboard');
    $response->assertStatus(200);
});