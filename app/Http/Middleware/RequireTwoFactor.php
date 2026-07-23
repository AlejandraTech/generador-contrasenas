<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->hasTwoFactorEnabled() && ! $request->session()->get('2fa:passed')) {
            return redirect()->route('twofactor.challenge');
        }

        return $next($request);
    }
}