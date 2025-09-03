<?php

namespace App\Providers;

use App\Models\ParoisseRetrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

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
        Schema::defaultStringLength(191);

        // Compartir el contador de retiros pendientes con todas las vistas de admin
        View::composer('*', function ($view) {
            if (Auth::guard('admin')->check()) {
                $pendingWithdrawalsCount = ParoisseRetrait::where('statut', 'en_attente')->count();
                $view->with('pendingWithdrawalsCount', $pendingWithdrawalsCount);
            }
        });
    }
}
