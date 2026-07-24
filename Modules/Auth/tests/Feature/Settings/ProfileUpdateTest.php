<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('profile page is displayed', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/cp/settings/profile');

    $response->assertOk();
});

test('profile information can be updated', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/cp/settings/profile', [
            'last_name' => 'User',
            'first_name' => 'Test',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors();
    //     ->assertRedirect('/cp/settings/profile');

    $user->refresh();

    expect($user->first_name)->toBe('Test');
    expect($user->last_name)->toBe('User');
    expect($user->email)->toBe('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/cp/settings/profile', [
            'last_name' => 'Test',
            'first_name' => 'User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors();
    // ->assertRedirect('/cp/settings/profile');

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});
