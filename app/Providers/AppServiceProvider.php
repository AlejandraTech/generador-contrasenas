<?php

namespace App\Providers;

use App\Services\PasswordGenerator;
use App\Services\ShareService;
use App\Services\StrengthAnalyzer;
use App\Services\TwoFactorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PasswordGenerator::class);
        $this->app->singleton(StrengthAnalyzer::class);
        $this->app->singleton(TwoFactorService::class);
        $this->app->singleton(ShareService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
