<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class TwoFactorController extends Controller
{
    public function __construct(private readonly TwoFactorService $twoFactor) {}

    public function enable(Request $request)
    {
        $user = Auth::user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('vault.index')->with('status', '2FA ya está activado.');
        }

        $secret = $this->twoFactor->generateSecretKey();
        $request->session()->put('2fa:pending_secret', Crypt::encryptString($secret));

        return view('twofactor.enable', [
            'qr' => $this->twoFactor->qrCodeInline($user, $secret),
            'secret' => $secret,
        ]);
    }

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ]);

        $encrypted = $request->session()->pull('2fa:pending_secret');
        if ($encrypted === null) {
            return redirect()->route('twofactor.enable')->withErrors(['code' => 'Sesión expirada, reintenta.']);
        }

        $secret = Crypt::decryptString($encrypted);

        if (! $this->twoFactor->verify($secret, $validated['code'])) {
            $request->session()->put('2fa:pending_secret', $encrypted);

            return back()->withErrors(['code' => 'Código incorrecto.']);
        }

        $user = Auth::user();
        $user->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_confirmed_at' => now(),
        ])->save();

        return redirect()->route('vault.index')->with('status', '2FA activado correctamente.');
    }

    public function disable(Request $request)
    {
        $user = Auth::user();
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return redirect()->route('vault.index')->with('status', '2FA desactivado.');
    }

    public function challenge()
    {
        $loginId = session('2fa:login_id');

        if ($loginId === null && (! Auth::check() || ! Auth::user()->hasTwoFactorEnabled())) {
            return redirect()->route('vault.index');
        }

        return view('twofactor.challenge');
    }

    public function verifyChallenge(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'digits:6'],
        ]);

        $loginId = $request->session()->pull('2fa:login_id');

        if ($loginId !== null) {
            $user = User::find($loginId);
            if ($user === null || ! $user->verifyTwoFactorCode($validated['code'])) {
                $request->session()->put('2fa:login_id', $loginId);

                return back()->withErrors(['code' => 'Código incorrecto.']);
            }
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            $request->session()->put('2fa:passed', true);

            return redirect()->intended(route('vault.index'));
        }

        if (Auth::check() && Auth::user()->verifyTwoFactorCode($validated['code'])) {
            $request->session()->put('2fa:passed', true);

            return redirect()->intended(route('vault.index'));
        }

        return back()->withErrors(['code' => 'Código incorrecto.']);
    }
}