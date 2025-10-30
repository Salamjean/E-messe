<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthenticateAdmin;
use App\Http\Controllers\Paroisse\ParoisseController;
use App\Http\Controllers\Admin\Paroisse\RetraitController;
use App\Http\Controllers\Admin\User\AdminUserController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Paroisse\AuthenticateParoisse;
use App\Http\Controllers\Paroisse\Demande\DemandeController;
use App\Http\Controllers\Paroisse\Offrande\OffrandeController;
use App\Http\Controllers\Paroisse\Paiement\ParoissePaiement;
use App\Http\Controllers\Paroisse\ParoisseDashboard;
use App\Http\Controllers\User\AuthenticateUser;
use App\Http\Controllers\User\Messe\MesseController;
use App\Http\Controllers\User\Messe\PaiementController;
use App\Http\Controllers\User\Messe\PaiementStripeController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserDashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Paroisse\Event\EventController;
use App\Http\Controllers\User\Event\EventController as UserEventController;


Route::get('/',[HomeController::class, 'home'])->name('home');

//Les routes de l'administrateur @admin
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthenticateAdmin::class, 'login'])->name('admin.login');
    Route::post('/login', [AuthenticateAdmin::class, 'handleLogin'])->name('admin.handleLogin');
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    //Les routes de destion des utilisateurs par l'admin 
    Route::prefix('users')->group(function () {
        Route::get('/indexed', [AdminUserController::class, 'index'])->name('admin.user.index');
        Route::delete('/{user}/archive', [AdminUserController::class, 'archive'])->name('users.archive');
        Route::get('/archived', [AdminUserController::class, 'archived'])->name('users.archived');
        Route::post('/{user}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
        Route::delete('/{user}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.force-delete');
    });  
    //Les routes de gestion de la paroisse par l'admin 
     Route::prefix('parish')->group(function () {
        Route::get('/indexparish', [ParoisseController::class, 'index'])->name('paroisse.index');
        Route::get('/createed', [ParoisseController::class, 'create'])->name('paroisse.create');
        Route::post('/createed', [ParoisseController::class, 'store'])->name('paroisse.store');
        Route::get('/{paroisse}/edit', [ParoisseController::class, 'edit'])->name('admin.paroisses.edit');
         Route::put('/{paroisse}', [ParoisseController::class, 'update'])->name('paroisse.update'); 
        Route::delete('/{paroisse}', [ParoisseController::class, 'destroy'])->name('admin.paroisses.destroy');
    });

    //Les routes de gestions de retraits par l'admin 
    Route::prefix('withdrawal')->group(function () {
        Route::get('/request/parishe', [RetraitController::class, 'request'])->name('admin.paroisse.index');
        Route::get('/parish/history', [RetraitController::class, 'history'])->name('admin.paroisse.history');
        Route::post('/{id}/confirmer', [RetraitController::class, 'confirmer'])->name('admin.retraits.confirmer');
         Route::post('/{id}/rejeter', [RetraitController::class, 'rejeter'])->name('admin.retraits.rejeter');
    });
    Route::get('/get-communes/{ville_id}', [ParoisseController::class, 'getCommunesByVille'])->name('admin.get.communes');
});

//Les routes des @paroisses 
Route::prefix('parish')->group(function() {
    Route::get('/login', [AuthenticateParoisse::class, 'login'])->name('paroisse.login');
    Route::post('/login', [AuthenticateParoisse::class, 'handleLogin'])->name('paroisse.handleLogin');
});

Route::middleware('paroisse')->prefix('parish')->group(function(){
    Route::get('/dahboard', [ParoisseDashboard::class, 'dashboard'])->name('paroisse.dashboard');
    Route::get('/logout', [ParoisseDashboard::class, 'logout'])->name('paroisse.logout');

    //retraits
    Route::post('/retrait/request', [ParoissePaiement::class, 'requestRetrait'])->name('paroisse.retrait.request');
    Route::get('/request/create', [ParoissePaiement::class, 'create'])->name('paroisse.retrait.create');
    Route::get('/retraits', [ParoissePaiement::class, 'index'])->name('paroisse.retraits');
    Route::get('/historye', [ParoissePaiement::class, 'history'])->name('paroisse.history');
    Route::delete('/retrait/{id}/annuler', [ParoissePaiement::class, 'annuler'])->name('paroisse.retrait.annuler');

     //Les routes pour modifier des informations de la paroisse 
    Route::get('/profile',[AuthenticateParoisse::class,'editProfile'])->name('paroisse.profile');
    Route::put('/profile/update', [AuthenticateParoisse::class, 'updateProfile'])->name('paroisse.update');

    //Routes de gestion des demandes de messes 
    Route::get('/index',[DemandeController::class,'index'])->name('demandes.messes.index');
    Route::get('/validate',[DemandeController::class,'validate'])->name('demandes.messes.validate');
    Route::get('/mes-messes/{messe}', [DemandeController::class, 'show'])->name('paroisse.messe.show');
    Route::post('/mes-messes/export-pdf', [DemandeController::class, 'exportPdf'])->name('paroisse.messe.export-pdf');
    Route::post('/mes-messes/{messe}/cancel', [DemandeController::class, 'cancel'])->name('paroisse.messe.cancel');
    Route::post('/mes-messes/{messe}/confirmed', [DemandeController::class, 'confirmed'])->name('paroisse.messe.confirmed');
    Route::post('/messe/update-status', [DemandeController::class, 'updateStatusToCelebrated'])->name('paroisse.messe.update-status');
    Route::post('/messess/bulk-confirm', [DemandeController::class, 'bulkConfirm'])->name('paroisse.messe.bulk-confirm');
    Route::post('/messess/bulk-cancel', [DemandeController::class, 'bulkCancel'])->name('paroisse.messe.bulk-cancel');

    //Routes de gestion des offrandes 
    Route::get('/offerings',[OffrandeController::class,'create'])->name('paroisse.offrande');
    Route::post('/parish/offrande', [OffrandeController::class, 'storeOffrande'])->name('paroisse.offrande.store');
    Route::get('/request/historys',[OffrandeController::class,'history'])->name('demandes.messes.history');

    Route::prefix('event')->name('event.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/data', [EventController::class, 'data'])->name('data');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}', [EventController::class, 'show'])->name('show');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
    });


});
//Les routes des @utilisateurs (@fideles)
Route::prefix('user')->group(function(){
    Route::get('/login',[AuthenticateUser::class,'login'])->name('login');
    Route::post('/login',[AuthenticateUser::class,'handleLogin'])->name('handleLogin');
    Route::get('/register',[AuthenticateUser::class,'register'])->name('register');
    Route::post('/register',[AuthenticateUser::class,'handleRegister'])->name('handleRegister');
});
Route::middleware('auth')->prefix('user')->group(function(){
     Route::get('/dashboard',[UserDashboard::class,'dashboard'])->name('user.dashboard');
     Route::get('/logout', [UserDashboard::class, 'logout'])->name('user.logout');

     //Les routes pour modifier le profil d'utilisateur
      Route::get('/profile',[AuthenticateUser::class,'editProfile'])->name('user.profile');
      Route::put('/profile/update', [AuthenticateUser::class, 'updateProfile'])->name('profile.update');

     //Les demandes de messes 
     Route::get('/index',[MesseController::class,'index'])->name('user.messe.index');
     Route::get('/create/massess',[MesseController::class,'create'])->name('user.messe.create');
     Route::post('/create/mass',[MesseController::class,'store'])->name('user.messe.store');
     Route::get('/mes-messes/{messe}', [MesseController::class, 'show'])->name('user.messe.show');
     Route::get('/masses/history',[MesseController::class,'history'])->name('user.messe.history');
     Route::delete('/mes-messes/{messe}', [MesseController::class, 'destroy'])->name('user.messe.destroy');
     Route::get('/mes-messes/{messe}/receipt', [MesseController::class, 'downloadReceipt'])->name('user.messe.receipt');

     // Routes pour le paiement
    Route::get('/messe/paiement/{reference}', [PaiementController::class, 'showPaiementForm'])->name('user.messe.paiement');
    Route::post('/messe/paiement/{reference}/initier', [PaiementController::class, 'initierPaiement'])->name('user.messe.initier-paiement');
    Route::get('/messe/paiement/{reference}/verification', [PaiementController::class, 'verifierPaiement'])->name('user.messe.verification-paiement'); 
    Route::post('/messe/paiement/{reference}/verifier', [PaiementController::class, 'verifierManuellement'])->name('user.messe.verifier-manuellement');

    //Les routes de paiement par stripe
    Route::post('/paiement/{reference}/stripe', [PaiementStripeController::class, 'initierPaiementStripe'])
        ->name('user.messe.initier-paiement-stripe');
    
    Route::get('/paiement/{reference}/stripe/success', [PaiementStripeController::class, 'successPaiementStripe'])
        ->name('user.messe.paiement-stripe.success');
    
    Route::get('/paiement/{reference}/stripe/cancel', [PaiementStripeController::class, 'cancelPaiementStripe'])
        ->name('user.messe.paiement-stripe.cancel');
    
    Route::post('/paiement/{reference}/stripe/verifier', [PaiementStripeController::class, 'verifierPaiementStripe'])
        ->name('user.messe.paiement-stripe.verifier');

    Route::prefix('user_event')->name('user_event.')->group(function () {
        Route::get('/', [UserEventController::class, 'index'])->name('index');
        Route::get('/data', [UserEventController::class, 'data'])->name('data');
        Route::post('/', [UserEventController::class, 'store'])->name('store');
        Route::get('/{event}', [UserEventController::class, 'show'])->name('show');
        Route::put('/{event}', [UserEventController::class, 'update'])->name('update');
        Route::delete('/{event}', [UserEventController::class, 'destroy'])->name('destroy');
    });
    
});
// NOUVEL EMPLACEMENT CORRECT POUR LES ROUTES DE DONNÉES
// J'ai aussi retiré /api car votre JS ne l'utilise pas.
Route::get('/get-communes/{ville_id}', [MesseController::class, 'getCommunes'])->name('get.communes');
Route::get('/get-paroisses/{commune_id}', [MesseController::class, 'getParoisses'])->name('get.paroisses');

//Les routes definition du accès 
Route::get('/validate-parish-account/{email}', [AuthenticateParoisse::class, 'defineAccess']);
Route::post('/validate-parish-account/{email}', [AuthenticateParoisse::class, 'submitDefineAccess'])->name('paroisse.validate');