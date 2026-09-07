<?php

namespace App\Http\Controllers\Redirectionpaiement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentRedirectController extends Controller
{
    /**
     * Redirection de compatibilité succès vers le contrôleur Wave
     */
    public function success(Request $request)
    {
        return app(RedirectController::class)->success($request);
    }

    /**
     * Redirection de compatibilité annulation vers le contrôleur Wave
     */
    public function cancel(Request $request)
    {
        return app(RedirectController::class)->error($request);
    }
}
