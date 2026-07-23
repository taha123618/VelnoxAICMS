<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Date::use(CarbonImmutable::class);

        Model::automaticallyEagerLoadRelationships();

        Model::unguard();

        JsonResource::withoutWrapping();

        Vite::useAggressivePrefetching();

        ResetPassword::createUrlUsing(fn (User $user, string $token) => URL::route('admin.password.reset', ['token' => $token, 'email' => $user->getEmailForPasswordReset()]));

    }
}
