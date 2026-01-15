<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isArchived()) {
            if ($request->expectsJson()) {
                Auth::user()->tokens()->delete();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Votre compte a été archivé. Veuillez contacter l\'administration.',
                ], 403);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Votre compte a été archivé. Veuillez contacter l\'administration.');
        }

        return $next($request);
    }
}
