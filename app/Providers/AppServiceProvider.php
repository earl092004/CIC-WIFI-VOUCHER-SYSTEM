<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Contracts\OmadaServiceInterface::class,
            fn () => env('APP_ENV') === 'production'
                ? new \App\Services\RealOmadaService
                : new \App\Services\MockOmadaService
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
