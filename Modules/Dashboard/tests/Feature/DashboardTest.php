<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('guests are redirected to the login page', function (): void {
    $response = $this->get('/cp/dashboard');
    $response->assertRedirect('/cp/login');
});

test('authenticated users can visit the dashboard', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/cp/dashboard');
    $response->assertStatus(200);
});
