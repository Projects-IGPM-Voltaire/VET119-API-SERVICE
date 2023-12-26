<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Events\MigrationsStarted;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Event::listen(MigrationsStarted::class, function () {
            if (config('databases.allow_disabled_pk')) {
                DB::statement('SET SESSION sql_require_primary_key=0');
            }
        });

        Event::listen(MigrationsEnded::class, function () {
            if (config('databases.allow_disabled_pk')) {
                DB::statement('SET SESSION sql_require_primary_key=1');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
