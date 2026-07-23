<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('page.{id}', function ($user, $id) {
    return ['id' => $user->id, 'name' => $user->name];
});

Broadcast::channel('layout.{id}', function ($user, $id) {
    return ['id' => $user->id, 'name' => $user->name];
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});

Broadcast::channel('ai-job.{id}', function ($user, $id) {
    return true;
});
