<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
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
        
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return URL::route('admin.password.reset', ['token' => $token, 'email' => $user->getEmailForPasswordReset()]);
        });

    }
}
