<?php

namespace App\Providers;

use App\Models\Support;
use App\Observers\SupportObserver;
use App\Repositories\{SupportEloquentORM};
use App\Repositories\Contracts\{ReplyRepositoryInterface, SupportRepositoryInterface};
use App\Repositories\Eloquent\ReplySupportRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
