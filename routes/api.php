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
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\FcmTokenController;
use App\Http\Controllers\Api\NotificationTestController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES (Pas besoin de connexion)
|--------------------------------------------------------------------------
*/

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/google', [AuthController::class, 'loginWithGoogle']);

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// --- PAIEMENT WAVE (Webhooks & Redirections) ---
Route::post('/paiement/wave/webhook', [WaveController::class, 'webhook'])->name('wave.webhook');
Route::get('/paiement/wave/verifier/{id}', [WaveController::class, 'verifier']);
Route::get('/paiement/wave/success', [WaveController::class, 'success'])->name('wave.success');
Route::get('/paiement/wave/error', [WaveController::class, 'error'])->name('wave.error');

// --- PAIEMENT CINETPAY (Webhooks & Redirections) ---
// IMPORTANT : Ces routes doivent être HORS du middleware auth:sanctum
Route::post('/paiement/cinetpay/webhook', [PaiementController::class, 'handleWebhook'])->name('cinetpay.webhook');
Route::any('/paiement/cinetpay/return', [PaiementController::class, 'paymentSuccess'])->name('cinetpay.success');
Route::any('/paiement/cinetpay/cancel', [PaiementController::class, 'paymentCancel'])->name('cinetpay.cancel');

// --- TESTS ---
Route::post('/test-firebase', [TestController::class, 'testFirebaseNotification']);

Route::get('/test-email', function () {
    try {
        Mail::raw('Test email configuration', function ($message) {
            $message->to('ledevpro03@gmail.com')
                    ->subject('Test Email');
        });
        return "Email envoyé avec succès!";
    } catch (\Exception $e) {
        return "Erreur: " . $e->getMessage();
    }
});

Route::get('/test-hostinger', function () {
    try {
        \Log::info('Testing Hostinger SMTP configuration...');
        \Mail::raw('Test Hostinger SMTP - ' . now(), function ($message) {
            $message->to('leprodev03@gmail.com')
                    ->subject('Test Hostinger SMTP')
                    ->from('contact@edemarchee-ci.com', 'E-Messe');
        });
        return "Email de test envoyé avec succès!";
    } catch (\Exception $e) {
        \Log::error('Hostinger SMTP Error: ' . $e->getMessage());
        return "Erreur Hostinger SMTP: " . $e->getMessage();
    }
});


/*
|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES (Nécessitent un Token Bearer)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // --- USER ---
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'profile']);
        Route::post('/', [UserController::class, 'updateProfile']); 
        Route::put('/change-password', [UserController::class, 'changePassword']);
        Route::put('/notifications', [UserController::class, 'updateNotifications']);
        Route::post('/verify-password', [UserController::class, 'verifyPassword']);
        Route::delete('/delete-account', [UserController::class, 'deleteAccount']);
    });
    
    // --- MESSES ---
    Route::prefix('messes')->group(function () {
        Route::get('/', [MesseController::class, 'index']);
        Route::post('/', [MesseController::class, 'store']);
        Route::get('/history', [MesseController::class, 'history']);
        Route::get('/en-cours', [MesseController::class, 'en_cours']);
        Route::get('/detail/{id}', [MesseController::class, 'show']);
        Route::get('/demandes-specifiques', [MesseController::class, 'demandesSpecifiques']);
        
        // Dynamiques à la fin
        Route::get('/{messe}', [MesseController::class, 'show']);
        Route::put('/{messe}', [MesseController::class, 'update']);
        Route::delete('/{messe}', [MesseController::class, 'destroy']);
    });

    // --- PAROISSES ---
    Route::prefix('paroisses')->group(function () {
        Route::get('/', [ParoisseController::class, 'index']);
        Route::post('/', [ParoisseController::class, 'store']);
        Route::get('/history', [ParoisseController::class, 'history']);
        Route::get('/demandes-specifiques', [ParoisseController::class, 'demandesSpecifiques']);
        Route::put('/{messe}', [ParoisseController::class, 'update']);
        Route::delete('/{messe}', [ParoisseController::class, 'destroy']);
        Route::get('/{id}', [ParoisseController::class, 'show']); 
        Route::get('/recommandations', [ParoisseController::class, 'recommandations']);
        Route::get('/check/{paroisse_id}', [ParoisseController::class, 'checkFavori']);
        Route::post('/toggle', [ParoisseController::class, 'toggleFavori']);
    });

    // --- FAVORIS ---
    Route::prefix('favoris')->group(function () {
        Route::get('/', [FavoriController::class, 'index']);
        Route::get('/check/{id}', [FavoriController::class, 'check']);
        Route::post('/', [FavoriController::class, 'store']);
        Route::delete('/{id}', [FavoriController::class, 'destroy']);
    });

    // --- PAIEMENT INIT (Wave & CinetPay) ---
    // Note: L'initialisation doit être protégée (car liée à un user), 
    // mais le webhook (réception) doit être public.
    Route::prefix('paiement')->group(function () {
        // Wave
        Route::post('/wave/checkout-url', [WaveController::class, 'checkoutUrl']);
        Route::post('/wave/initier', [WaveController::class, 'initier']);
        
        // CinetPay
        Route::post('/cinetpay/initier', [PaiementController::class, 'initierPaiement']);
    });

    // --- EVENTS ---
    Route::prefix('event')->group(function () {
        Route::get('/', [EventController::class, 'index']); 
        Route::get('/{id}', [EventController::class, 'show']);
        Route::post('/name', [EventController::class, 'event_name']);
    });

    // --- NOTIFICATIONS & DIVERS ---
    Route::patch('/users/{id}/notifications', [UserNotificationController::class, 'updateAll']);
    Route::get('/verset-du-jour', [VersetController::class, 'verset_du_jour']); 

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::put('/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
        Route::delete('/clear-all', [NotificationController::class, 'clearAll']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
        Route::get('detail/{id}', [NotificationController::class, 'showMesseDetails']);
        Route::get('/event/{evenement_id}', [EventController::class, 'showFromNotification']);
    });

    Route::post('/fcm-token', [FcmTokenController::class, 'store']);
    Route::post('/test-notification', [NotificationTestController::class, 'sendTestNotification']);
});