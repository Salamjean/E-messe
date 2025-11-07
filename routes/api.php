<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Messe\MesseController;
use App\Http\Controllers\Api\Paroisse\ParoisseController;
use App\Http\Controllers\Api\Paroisse\FavoriController;
use App\Http\Controllers\Api\Paiement\PaiementController;
use App\Http\Controllers\Api\Event\EventController;
use App\Http\Controllers\Api\User\UserNotificationController;
use App\Http\Controllers\Api\Divers\VersetController;
use App\Http\Controllers\Api\Paiement\WaveController;


Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/google', [AuthController::class, 'loginWithGoogle']);

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::post('/paiement/wave/webhook', [WaveController::class, 'webhook'])->name('wave.webhook');
Route::get('/paiement/wave/verifier/{id}', [WaveController::class, 'verifier']);

// ✅ Routes pour redirection après paiement
Route::get('/paiement/wave/success', [WaveController::class, 'success'])->name('wave.success');
Route::get('/paiement/wave/error', [WaveController::class, 'error'])->name('wave.error');



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    //Le groupe de route user/profil
    Route::prefix('user')->group(function () {

        Route::get('/', [UserController::class, 'profile']);
        // Utilisez PATCH pour les mises à jour partielles
        Route::put('/', [UserController::class, 'updateProfile']);
        Route::put('/change-password', [UserController::class, 'changePassword']);
        Route::put('/notifications', [UserController::class, 'updateNotifications']);
    });

    
    //Route pour les demande de messe
    Route::prefix('messes')->group(function () {
        Route::get('/', [MesseController::class, 'index']);
        Route::post('/', [MesseController::class, 'store']);

        // Les routes avec un nom fixe en premier
        Route::get('/history', [MesseController::class, 'history']);
        Route::get('/en-cours', [MesseController::class, 'en_cours']);
        Route::get('/detail/{id}', [MesseController::class, 'show']);

        Route::get('/demandes-specifiques', [MesseController::class, 'demandesSpecifiques']);

        // Les routes dynamiques à la fin
        Route::get('/{messe}', [MesseController::class, 'show']);
        Route::put('/{messe}', [MesseController::class, 'update']);
        Route::delete('/{messe}', [MesseController::class, 'destroy']);
    });

    //Route pour les paroisses
    Route::prefix('paroisses')->group(function () {
        Route::get('/', [ParoisseController::class, 'index']);
        Route::post('/', [ParoisseController::class, 'store']);

        // ✅ Les routes avec un nom fixe en premier
        Route::get('/history', [ParoisseController::class, 'history']);
        Route::get('/demandes-specifiques', [ParoisseController::class, 'demandesSpecifiques']);

        // ⚠️ Les routes dynamiques à la fin
        // Route::get('/{messe}', [ParoisseController::class, 'show']);
        Route::put('/{messe}', [ParoisseController::class, 'update']);
        Route::delete('/{messe}', [ParoisseController::class, 'destroy']);

        Route::get('/{id}', [ParoisseController::class, 'show']); // Détails d'une paroisse
        Route::get('/recommandations', [ParoisseController::class, 'recommandations']);
        Route::get('/check/{paroisse_id}', [ParoisseController::class, 'checkFavori']);
        Route::post('/toggle', [ParoisseController::class, 'toggleFavori']);// ajouter ou retirer un favori en un seul clic


    });

    //Route pour les favoris
    Route::prefix('favoris')->group(function () {
        Route::get('/', [FavoriController::class, 'index']);       // Liste favoris
        Route::get('/check/{id}', [FavoriController::class, 'check']);
        Route::post('/', [FavoriController::class, 'store']);      // Ajouter un favori
        Route::delete('/{id}', [FavoriController::class, 'destroy']); // Supprimer un favori
        

    });

        //Route pour les paiement
    Route::prefix('paiement')->group(function () {
        Route::post('/wave/checkout-url', [WaveController::class, 'checkoutUrl']);
        Route::post('/wave/initier', [WaveController::class, 'initier']);
    });

    //Route pour les évènements
    Route::prefix('event')->group(function () {
        Route::get('/', [EventController::class, 'index']); 
        Route::get('/{id}', [EventController::class, 'show']);
        Route::post('/name', [EventController::class, 'event_name']);

    });

    //route pour les notification
    Route::patch('/users/{id}/notifications', [UserNotificationController::class, 'updateAll']);

    //route pour les versets
    Route::get('/verset-du-jour', [VersetController::class, 'verset_du_jour']); 



    Route::prefix('notification')->group(function () {
        Route::post('/save-fcm-token', [FcmTokenController::class, 'store']);
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);


    });

});

