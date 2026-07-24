<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('confirm password screen can be rendered', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/cp/confirm-password');

    $response->assertStatus(200);
});

test('password can be confirmed', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/cp/confirm-password', [
        'password' => 'password',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

test('password is not confirmed with invalid password', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/cp/confirm-password', [
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
});
