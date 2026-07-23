<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthenticatedSessionController;
use Modules\Auth\Http\Controllers\ChangePasswordController;
use Modules\Auth\Http\Controllers\ConfirmablePasswordController;
use Modules\Auth\Http\Controllers\EmailVerificationNotificationController;
use Modules\Auth\Http\Controllers\EmailVerificationPromptController;
use Modules\Auth\Http\Controllers\NewPasswordController;
use Modules\Auth\Http\Controllers\PasswordResetLinkController;
use Modules\Auth\Http\Controllers\ProfileUpdateController;
use Modules\Auth\Http\Controllers\RegisteredUserController;
use Modules\Auth\Http\Controllers\RoleController;
use Modules\Auth\Http\Controllers\RolePermissionsController;
use Modules\Auth\Http\Controllers\UserController;
use Modules\Auth\Http\Controllers\UserSendPasswordResetLinkController;
use Modules\Auth\Http\Controllers\VerifyEmailController;

Route::group(['as' => 'admin.', 'prefix' => 'cp'], function (): void {
    Route::middleware(['guest', 'demo.protect'])->group(function (): void {

        Route::get('register', [RegisteredUserController::class, 'create'])
            ->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store'])
            ->name('login');

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.store');
    });

    Route::middleware(['auth', 'demo.protect'])->group(function (): void {
        Route::get('settings/profile', [ProfileUpdateController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('settings/profile', [ProfileUpdateController::class, 'update'])
            ->name('profile.update');

        Route::get('settings/password', [ChangePasswordController::class, 'edit'])
            ->name('password.edit');

        Route::put('settings/password', [ChangePasswordController::class, 'update'])
            ->name('password.update');

        Route::get('verify-email', EmailVerificationPromptController::class)
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        Route::post('users/{user}/reset-link', UserSendPasswordResetLinkController::class)
            ->name('users.password.email');

        Route::resource('users', UserController::class)
            ->except(['show'])
            ->names('users');

        Route::resource('roles', RoleController::class)
            ->except(['show'])
            ->names('roles');

        Route::get('roles/{role}/permissions', [RolePermissionsController::class, 'edit'])
            ->name('roles.permissions');

        Route::put('roles/{role}/permissions', [RolePermissionsController::class, 'update']);
    });
});
