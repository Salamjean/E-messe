<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\Notification;

class CustomNotificationProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DatabaseNotification::class, Notification::class);
    }

    public function boot(): void
    {
        //
    }
}
