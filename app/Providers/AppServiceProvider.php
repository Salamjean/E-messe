<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use App\Notifications\Channels\FcmHttpChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrer le canal personnalisé
        Notification::extend('fcm_http', function ($app) {
            return new FcmHttpChannel();
        });

        // Eviter l'erreur "clé trop longue" avec MySQL utf8mb4
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
