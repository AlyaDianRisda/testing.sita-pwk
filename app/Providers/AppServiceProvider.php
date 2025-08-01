<?php
namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        /*
        Schema::defaultStringLength(191);

        // Khusus SQLite: aktifkan foreign key constraint
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=ON;');
        }
        */
    }
}
