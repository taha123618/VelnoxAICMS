<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', fn ($user, $id): bool => (int) $user->id === (int) $id);

Broadcast::channel('page.{id}', fn ($user, $id): array => ['id' => $user->id, 'name' => $user->name]);

Broadcast::channel('layout.{id}', fn ($user, $id): array => ['id' => $user->id, 'name' => $user->name]);

Broadcast::channel('user.{id}', fn ($user, $id): bool => (string) $user->id === (string) $id);

Broadcast::channel('ai-job.{id}', fn ($user, $id): true => true);
