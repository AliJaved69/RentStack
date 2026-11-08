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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-seed preloaded laptop data into SQLite on initial APK boot
        try {
            \App\Services\DataSyncService::importIfEmpty();
        } catch (\Throwable $e) {
            // Ignore during migrations or before tables are initialized
        }
    }
}
