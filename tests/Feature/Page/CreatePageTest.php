<?php

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Modules\Auth\Models\User;
use Modules\Layout\Models\Layout;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('successfully creates a page even if user current_tenant_id is unassigned', function () {
    Gate::before(fn () => true);

    $tenant = Tenant::create([
        'name' => 'Default Workspace',
        'slug' => 'default-workspace',
        'is_active' => true,
    ]);

    $user = User::factory()->create([
        'current_tenant_id' => null,
    ]);

    $layout = Layout::create([
        'name' => 'Default Layout',
        'content' => [],
    ]);

    $response = actingAs($user)->post('/cp/pages', [
        'title' => 'New Test Page',
        'layout' => $layout->id,
        'content' => [
            [
                'id' => 'wrapper_1',
                'type' => 'wrapper',
                'canDrop' => true,
                'isLayoutElement' => false,
                'name' => 'Wrapper',
            ],
        ],
    ]);

    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('pages', [
        'title' => 'New Test Page',
        'tenant_id' => $tenant->id,
    ]);

    $user->refresh();
    expect($user->current_tenant_id)->toBe($tenant->id);
});
