<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthenticateAdmin;
use App\Http\Controllers\Admin\ContentManagementController;
use App\Http\Controllers\Admin\Paroisse\RetraitController;
use App\Http\Controllers\Admin\User\AdminUserController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Paroisse\AuthenticateParoisse;
use App\Http\Controllers\Paroisse\Demande\DemandeController;
use App\Http\Controllers\Paroisse\Event\EventController;
use App\Http\Controllers\Paroisse\Offrande\OffrandeController;
use App\Http\Controllers\Paroisse\Paiement\ParoissePaiement;
use App\Http\Controllers\Paroisse\ParoisseController;
use App\Http\Controllers\Paroisse\ParoisseDashboard;
use App\Http\Controllers\Paroisse\Paroissien\ParoissienController;
use App\Http\Controllers\Redirectionpaiement\PaymentRedirectController;
use App\Http\Controllers\Redirectionpaiement\RedirectController;
use App\Http\Controllers\User\AuthenticateUser;
use App\Http\Controllers\User\Event\EventController as UserEventController;
use App\Http\Controllers\User\Messe\MesseController;
use App\Http\Controllers\User\Messe\PaiementController;
use App\Http\Controllers\User\Messe\PaiementStripeController;
use App\Http\Controllers\User\ParoisseController as UserParoisseController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\UserDashboard;
use App\Http\Controllers\User\FicheController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/fonctionnalites', [HomeController::class, 'fonctionnalites'])->name('fonctionnalites');
Route::get('/messes', [HomeController::class, 'messes'])->name('messes');
Route::get('/paroisse', [HomeController::class, 'paroisses'])->name('paroisses');
Route::get('/evenements', [HomeController::class, 'evenements'])->name('evenements');
Route::get('/avantages', [HomeController::class, 'avantages'])->name('avantages');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/send', [HomeController::class, 'sendMessage'])->name('contact.send');

Route::get('/forgot-password', [AuthenticateUser::class, 'showForgotPasswordForm'])->name('forgot-password.form');
Route::post('/forgot-password', [AuthenticateUser::class, 'forgotPassword'])->name('forgot-password.send');

Route::get('/verify-otp', [AuthenticateUser::class, 'showVerifyOtpForm'])->name('verify-otp.form');
Route::post('/verify-otp', [AuthenticateUser::class, 'verifyOtp'])->name('verify-otp.check');

Route::get('/reset-password', [AuthenticateUser::class, 'showResetPasswordForm'])->name('reset-password.form');
Route::post('/reset-password', [AuthenticateUser::class, 'resetPassword'])->name('reset-password.update');

// Route::prefix('paiement/cinetpay')->group(function () {
//     Route::get('/success', [PaymentRedirectController::class, 'success'])->name('cinetpay.success');
//     Route::get('/cancel', [PaymentRedirectController::class, 'cancel'])->name('cinetpay.cancel');
// });

Route::prefix('paiement/cinetpay')->group(function () {
    Route::match(['get', 'post'], '/success', [PaymentRedirectController::class, 'success'])->name('cinetpay.success');
    Route::match(['get', 'post'], '/cancel', [PaymentRedirectController::class, 'cancel'])->name('cinetpay.cancel');
});

// ✅ Routes pour redirection après paiement
Route::get('/paiement/wave/success', [RedirectController::class, 'success'])->name('wave.success');
Route::get('/paiement/wave/error', [RedirectController::class, 'error'])->name('wave.error');

// Les routes de l'administrateur @admin
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthenticateAdmin::class, 'login'])->name('admin.login');
    Route::post('/login', [AuthenticateAdmin::class, 'handleLogin'])->name('admin.handleLogin');
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Les routes de destion des utilisateurs par l'admin
    Route::prefix('users')->group(function () {
        Route::get('/indexed', [AdminUserController::class, 'index'])->name('admin.user.index');
        Route::delete('/{user}/archive', [AdminUserController::class, 'archive'])->name('users.archive');
        Route::get('/archived', [AdminUserController::class, 'archived'])->name('users.archived');
        Route::post('/{user}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
        Route::delete('/{user}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.force-delete');
    });
    // Les routes de gestion de la paroisse par l'admin
    Route::prefix('parish')->group(function () {
        Route::get('/indexparish', [ParoisseController::class, 'index'])->name('paroisse.index');
        Route::get('/createed', [ParoisseController::class, 'create'])->name('paroisse.create');
        Route::post('/createed', [ParoisseController::class, 'store'])->name('paroisse.store');
        Route::get('/{paroisse}/edit', [ParoisseController::class, 'edit'])->name('admin.paroisses.edit');
        Route::put('/{paroisse}', [ParoisseController::class, 'update'])->name('paroisse.update');
        Route::delete('/{paroisse}', [ParoisseController::class, 'destroy'])->name('admin.paroisses.destroy');

    });
    // Les routes de gestions de retraits par l'admin
    Route::prefix('withdrawal')->group(function () {
        Route::get('/request/parishe', [RetraitController::class, 'request'])->name('admin.paroisse.index');
        Route::get('/parish/history', [RetraitController::class, 'history'])->name('admin.paroisse.history');
        Route::post('/{id}/confirmer', [RetraitController::class, 'confirmer'])->name('admin.retraits.confirmer');
        Route::post('/{id}/rejeter', [RetraitController::class, 'rejeter'])->name('admin.retraits.rejeter');
    });
    Route::get('/get-communes/{ville_id}', [ParoisseController::class, 'getCommunesByVille'])->name('admin.get.communes');

    // Routes de gestion des contenus dynamiques

    Route::prefix('content')->name('content.')->group(function () {
        // Statistiques accueil
        Route::get('/home-statistics', [ContentManagementController::class, 'homeStatistics'])->name('home-statistics');
        Route::post('/home-statistics', [ContentManagementController::class, 'homeStatisticsUpdate'])->name('home-statistics.update');

        // Témoignages
        Route::get('/testimonials', [ContentManagementController::class, 'testimonialsIndex'])->name('testimonials.index');
        Route::get('/testimonials/created_testimonial', [ContentManagementController::class, 'testimonialsCreate'])->name('testimonials.create');
        Route::post('/testimonials', [ContentManagementController::class, 'testimonialsStore'])->name('testimonials.store');
        Route::get('/testimonials/{testimonial}/edit', [ContentManagementController::class, 'testimonialsEdit'])->name('testimonials.edit');
        Route::put('/testimonials/{testimonial}', [ContentManagementController::class, 'testimonialsUpdate'])->name('testimonials.update');
        Route::delete('/testimonials/{testimonial}', [ContentManagementController::class, 'testimonialsDestroy'])->name('testimonials.destroy');

        // Succès paroisses
        Route::get('/parish-success', [ContentManagementController::class, 'parishSuccessIndex'])->name('parish-success.index');
        Route::get('/parish-success/created_success', [ContentManagementController::class, 'parishSuccessCreate'])->name('parish-success.create');
        Route::post('/parish-success', [ContentManagementController::class, 'parishSuccessStore'])->name('parish-success.store');
        Route::get('/parish-success/{story}/edit', [ContentManagementController::class, 'parishSuccessEdit'])->name('parish-success.edit');
        Route::put('/parish-success/{story}', [ContentManagementController::class, 'parishSuccessUpdate'])->name('parish-success.update');
        Route::delete('/parish-success/{story}', [ContentManagementController::class, 'parishSuccessDestroy'])->name('parish-success.destroy');

        // FAQs paroisses
        Route::get('/parish-faqs', [ContentManagementController::class, 'parishFaqsIndex'])->name('parish-faqs.index');
        Route::get('/parish-faqs/create', [ContentManagementController::class, 'parishFaqsCreate'])->name('parish-faqs.create');
        Route::post('/parish-faqs', [ContentManagementController::class, 'parishFaqsStore'])->name('parish-faqs.store');
        Route::get('/parish-faqs/{faq}/edit', [ContentManagementController::class, 'parishFaqsEdit'])->name('parish-faqs.edit');
        Route::put('/parish-faqs/{faq}', [ContentManagementController::class, 'parishFaqsUpdate'])->name('parish-faqs.update');
        Route::delete('/parish-faqs/{faq}', [ContentManagementController::class, 'parishFaqsDestroy'])->name('parish-faqs.destroy');

        // Impacts avantages
        Route::get('/advantage-impacts', [ContentManagementController::class, 'advantageImpactsIndex'])->name('advantage-impacts.index');
        Route::get('/advantage-impacts/create', [ContentManagementController::class, 'advantageImpactsCreate'])->name('advantage-impacts.create');
        Route::post('/advantage-impacts', [ContentManagementController::class, 'advantageImpactsStore'])->name('advantage-impacts.store');
        Route::get('/advantage-impacts/{impact}/edit', [ContentManagementController::class, 'advantageImpactsEdit'])->name('advantage-impacts.edit');
        Route::put('/advantage-impacts/{impact}', [ContentManagementController::class, 'advantageImpactsUpdate'])->name('advantage-impacts.update');
        Route::delete('/advantage-impacts/{impact}', [ContentManagementController::class, 'advantageImpactsDestroy'])->name('advantage-impacts.destroy');

        // Informations contact
        Route::get('/contact-infos', [ContentManagementController::class, 'contactInfosIndex'])->name('contact-infos.index');
        Route::get('/contact-infos/created_contact', [ContentManagementController::class, 'contactInfosCreate'])->name('contact-infos.create');
        Route::post('/contact-infos', [ContentManagementController::class, 'contactInfosStore'])->name('contact-infos.store');
        Route::get('/contact-infos/{info}/edit', [ContentManagementController::class, 'contactInfosEdit'])->name('contact-infos.edit');
        Route::put('/contact-infos/{info}', [ContentManagementController::class, 'contactInfosUpdate'])->name('contact-infos.update');
        Route::delete('/contact-infos/{info}', [ContentManagementController::class, 'contactInfosDestroy'])->name('contact-infos.destroy');

        // FAQs contact
        Route::get('/contact-faqs', [ContentManagementController::class, 'contactFaqsIndex'])->name('contact-faqs.index');
        Route::get('/contact-faqs/create_contactfaqs', [ContentManagementController::class, 'contactFaqsCreate'])->name('contact-faqs.create');
        Route::post('/contact-faqs', [ContentManagementController::class, 'contactFaqsStore'])->name('contact-faqs.store');
        Route::get('/contact-faqs/{faq}/edit', [ContentManagementController::class, 'contactFaqsEdit'])->name('contact-faqs.edit');
        Route::put('/contact-faqs/{faq}', [ContentManagementController::class, 'contactFaqsUpdate'])->name('contact-faqs.update');
        Route::delete('/contact-faqs/{faq}', [ContentManagementController::class, 'contactFaqsDestroy'])->name('contact-faqs.destroy');

        // Horaires support
        Route::get('/support-hours', [ContentManagementController::class, 'supportHoursIndex'])->name('support-hours.index');
        Route::get('/support-hours/created_supporthours', [ContentManagementController::class, 'supportHoursCreate'])->name('support-hours.create');
        Route::post('/support-hours', [ContentManagementController::class, 'supportHoursStore'])->name('support-hours.store');
        Route::get('/support-hours/{hour}/edit', [ContentManagementController::class, 'supportHoursEdit'])->name('support-hours.edit');
        Route::put('/support-hours/{hour}', [ContentManagementController::class, 'supportHoursUpdate'])->name('support-hours.update');
        Route::delete('/support-hours/{hour}', [ContentManagementController::class, 'supportHoursDestroy'])->name('support-hours.destroy');

        // Messages de contact
        Route::get('/contact-messages', [ContentManagementController::class, 'contactMessagesIndex'])->name('contact-messages.index');
        Route::delete('/contact-messages/{message}', [ContentManagementController::class, 'contactMessagesDestroy'])->name('contact-messages.destroy');
    });
});

// Les routes des @paroisses
Route::prefix('parish')->group(function () {
    Route::get('/login', [AuthenticateParoisse::class, 'login'])->name('paroisse.login');
    Route::post('/login', [AuthenticateParoisse::class, 'handleLogin'])->name('paroisse.handleLogin');
});

Route::middleware('paroisse')->prefix('parish')->group(function () {
    Route::get('/dahboard', [ParoisseDashboard::class, 'dashboard'])->name('paroisse.dashboard');
    Route::post('/dahboard', [ParoisseDashboard::class, 'dashboard'])->name('paroisse.dashboard');
    Route::get('/logout', [ParoisseDashboard::class, 'logout'])->name('paroisse.logout');

    // retraits
    Route::post('/retrait/request', [ParoissePaiement::class, 'requestRetrait'])->name('paroisse.retrait.request');
    Route::get('/request/created_retrait', [ParoissePaiement::class, 'create'])->name('paroisse.retrait.create');
    Route::get('/retraits', [ParoissePaiement::class, 'index'])->name('paroisse.retraits');
    Route::get('/historye', [ParoissePaiement::class, 'history'])->name('paroisse.history');
    Route::delete('/retrait/{id}/annuler', [ParoissePaiement::class, 'annuler'])->name('paroisse.retrait.annuler');

    // Les routes pour modifier des informations de la paroisse
    Route::get('/profile', [AuthenticateParoisse::class, 'editProfile'])->name('paroisse.profile');
    Route::put('/profile/update', [AuthenticateParoisse::class, 'updateProfile'])->name('paroisse.profile.update');

    // Routes de gestion des demandes de messes
    Route::get('/index/messes', [DemandeController::class, 'index'])->name('demandes.messes.index');
    Route::get('/validate', [DemandeController::class, 'validate'])->name('demandes.messes.validate');
    Route::get('/celebrated', [DemandeController::class, 'celebrated'])->name('demandes.messes.celebrated');
    Route::get('/messes_show/{messe}/show', [DemandeController::class, 'show'])->name('paroisse.messe_show');
    Route::get('/mes-messes_show_details/{messe}', [DemandeController::class, 'show_details'])->name('paroisse.messe.show_details');
    Route::post('/mes-messes/export-pdf', [DemandeController::class, 'exportPdf'])->name('paroisse.messe.export-pdf');
    Route::post('/mes-messes/{messe}/cancel', [DemandeController::class, 'cancel'])->name('paroisse.messe.cancel');
    Route::post('/mes-messes/{messe}/confirmed', [DemandeController::class, 'confirmed'])->name('paroisse.messe.confirmed');
    Route::post('/messe/update-status', [DemandeController::class, 'updateStatusToCelebrated'])->name('paroisse.messe.update-status');
    Route::post('/messess/bulk-confirm', [DemandeController::class, 'bulkConfirm'])->name('paroisse.messe.bulk-confirm');
    Route::post('/messess/bulk-cancel', [DemandeController::class, 'bulkCancel'])->name('paroisse.messe.bulk-cancel');

    // Routes de gestion des offrandes
    Route::get('/offerings', [OffrandeController::class, 'create'])->name('paroisse.offrande');
    Route::post('event/store', [EventController::class, 'store'])->name('event.store');

    Route::post('/parish/offrande', [OffrandeController::class, 'storeOffrande'])->name('paroisse.offrande.store');
    Route::get('/request/historys', [OffrandeController::class, 'history'])->name('demandes.messes.history');

    Route::prefix('reversement')->name('reversement.')->group(function () {
        Route::get('/', [ParoissePaiement::class, 'list_reversement'])->name('list_reversement');
        Route::get('/data', [ParoissePaiement::class, 'getData'])->name('data');
        Route::post('/store', [ParoissePaiement::class, 'store'])->name('store');
        Route::post('/notification', [ParoissePaiement::class, 'handleNotification'])->name('notification');
    });

    Route::prefix('event')->name('event.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/data', [EventController::class, 'data'])->name('data');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}', [EventController::class, 'show'])->name('show');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('paroissien')->name('paroissien.')->group(function () {

        // 1. Routes pour les Exports et AJAX (doivent être AVANT les routes avec ID)
        Route::get('/data', [ParoissienController::class, 'data'])->name('data');
        Route::get('/export/pdf', [ParoissienController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [ParoissienController::class, 'exportExcel'])->name('export.excel');

        // 2. Routes CRUD standard
        Route::get('/index_paroissien', [ParoissienController::class, 'index'])->name('index');
        Route::get('/create_paroissien', [ParoissienController::class, 'create'])->name('create');
        Route::post('/store_paroissien', [ParoissienController::class, 'store'])->name('store');

        // Routes avec paramètre {paroissien} (ID)
        Route::get('/{paroissien}', [ParoissienController::class, 'show'])->name('show');
        Route::get('/{paroissien}/edit', [ParoissienController::class, 'edit'])->name('edit');
        Route::put('/{paroissien}', [ParoissienController::class, 'update'])->name('update');
        Route::delete('/{paroissien}', [ParoissienController::class, 'destroy'])->name('destroy');

    });

});
// Les routes des @utilisateurs (@fideles)
Route::prefix('user')->group(function () {
    Route::get('/login', [AuthenticateUser::class, 'login'])->name('login');
    Route::post('/login', [AuthenticateUser::class, 'handleLogin'])->name('handleLogin');
    Route::get('/register', [AuthenticateUser::class, 'register'])->name('register');
    Route::post('/register', [AuthenticateUser::class, 'handleRegister'])->name('handleRegister');
});
Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'dashboard'])->name('user.dashboard');
    Route::get('/logout', [UserDashboard::class, 'logout'])->name('user.logout');

    // Les routes pour modifier le profil d'utilisateur
    Route::get('/profile', [AuthenticateUser::class, 'editProfile'])->name('user.profile');
    Route::put('/profile/update', [AuthenticateUser::class, 'updateProfile'])->name('profile.update');

    // Les demandes de messes
    Route::get('/index', [MesseController::class, 'index'])->name('user.messe.index');
    Route::get('/create/massess', [MesseController::class, 'create'])->name('user.messe.create');
    Route::post('/create/mass', [MesseController::class, 'store'])->name('user.messe.store');
    Route::get('/mes-messes/{messe}', [MesseController::class, 'show'])->name('user.messe.show');
    Route::get('/masses/history', [MesseController::class, 'history'])->name('user.messe.history');
    Route::get('/masses/historique_messes', [MesseController::class, 'historique_messes'])->name('user.messe.historique_messes');
    Route::get('/masses/hold', [MesseController::class, 'hold'])->name('user.messe.hold');
    Route::delete('/mes-messes/{messe}', [MesseController::class, 'destroy'])->name('user.messe.destroy');
    Route::get('/mes-messes/{messe}/receipt', [MesseController::class, 'downloadReceipt'])->name('user.messe.receipt');

    // Route pour lister les paroisses
    Route::get('/paroisses', [UserParoisseController::class, 'index'])->name('user.paroisse.index');
    Route::post('/paroisses/favorite/{id}', [UserParoisseController::class, 'toggleFavorite'])->name('user.paroisse.favorite');
    Route::get('/paroisse/{paroisse}', [UserParoisseController::class, 'show'])->name('user.paroisse.show');

    // Routes pour le paiement
    Route::get('/messe/paiement/{reference}', [PaiementController::class, 'showPaiementForm'])->name('user.messe.paiement');
    Route::post('/messe/paiement/{reference}/initier', [PaiementController::class, 'initierPaiement'])->name('user.messe.initier-paiement');
    Route::get('/messe/paiement/{reference}/verification', [PaiementController::class, 'verifierPaiement'])->name('user.messe.verification-paiement');
    Route::post('/messe/paiement/{reference}/verifier', [PaiementController::class, 'verifierManuellement'])->name('user.messe.verifier-manuellement');

    // Les routes de paiement par stripe
    Route::post('/paiement/{reference}/stripe', [PaiementStripeController::class, 'initierPaiementStripe'])
        ->name('user.messe.initier-paiement-stripe');

    Route::get('/paiement/{reference}/stripe/success', [PaiementStripeController::class, 'successPaiementStripe'])
        ->name('user.messe.paiement-stripe.success');

    Route::get('/paiement/{reference}/stripe/cancel', [PaiementStripeController::class, 'cancelPaiementStripe'])
        ->name('user.messe.paiement-stripe.cancel');

    Route::post('/paiement/{reference}/stripe/verifier', [PaiementStripeController::class, 'verifierPaiementStripe'])
        ->name('user.messe.paiement-stripe.verifier');

    // Routes pour les paramètres
    Route::prefix('settings')->name('user.settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/profile', [SettingsController::class, 'editProfile'])->name('profile');
        Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('updateProfile');
        Route::get('/password', [SettingsController::class, 'password'])->name('password');
        Route::put('/password', [SettingsController::class, 'updatePassword'])->name('updatePassword');
    });

    Route::prefix('user_event')->name('user_event.')->group(function () {
        Route::get('/', [UserEventController::class, 'index'])->name('index');
        Route::get('/data', [UserEventController::class, 'data'])->name('data');
        Route::post('/', [UserEventController::class, 'store'])->name('store');
        Route::get('/{event}', [UserEventController::class, 'show'])->name('show');
        Route::put('/{event}', [UserEventController::class, 'update'])->name('update');
        Route::delete('/{event}', [UserEventController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('fiche')->name('user.fiche.')->group(function () {
        Route::get('/', [FicheController::class, 'create'])->name('create');
        Route::post('/', [FicheController::class, 'store'])->name('store');
    });

});
// NOUVEL EMPLACEMENT CORRECT POUR LES ROUTES DE DONNÉES
// J'ai aussi retiré /api car votre JS ne l'utilise pas.
Route::get('/get-communes/{ville_id}', [MesseController::class, 'getCommunes'])->name('get.communes');
Route::get('/get-paroisses/{commune_id}', [MesseController::class, 'getParoisses'])->name('get.paroisses');

// Les routes definition du accès
Route::get('/validate-parish-account/{email}', [AuthenticateParoisse::class, 'defineAccess']);
Route::post('/validate-parish-account/{email}', [AuthenticateParoisse::class, 'submitDefineAccess'])->name('paroisse.validate');
