<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Channels\FcmHttpChannel;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Enregistrer le canal personnalisé
        Notification::extend('fcm_http', function ($app) {
            return new FcmHttpChannel();
        });
    }
}