<?php

namespace App\Providers;

use App\Services\QuranFoundationClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(QuranFoundationClient::class, fn () => new QuranFoundationClient(
            clientId: (string) config('services.quran_foundation.client_id'),
            clientSecret: (string) config('services.quran_foundation.client_secret'),
            tokenUrl: (string) config('services.quran_foundation.token_url'),
            apiBaseUrl: (string) config('services.quran_foundation.api_base_url'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            try {
                $view->with('globalSchoolSetting', \App\Models\SchoolSetting::getSettings());
            } catch (\Throwable $e) {
                $view->with('globalSchoolSetting', null);
            }
        });
    }
}
