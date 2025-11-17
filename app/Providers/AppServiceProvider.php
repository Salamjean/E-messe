<?php

namespace App\Providers;

use App\Models\ParoisseRetrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Notification; // <-- ajouté
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Partager le compteur de retraits en attente avec toutes les vues admin
        View::composer('*', function ($view) {
            if (Auth::guard('admin')->check()) {
                $pendingWithdrawalsCount = ParoisseRetrait::where('statut', 'en_attente')->count();
                $view->with('pendingWithdrawalsCount', $pendingWithdrawalsCount);
            }
        });

        // Enregistrer le canal personnalisé fcm_http
        Notification::extend('fcm_http', function ($app) {
            return new \App\Channels\FcmHttpChannel();
        });
    }
}
