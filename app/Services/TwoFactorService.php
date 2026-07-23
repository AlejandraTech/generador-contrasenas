<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FAQRCode\Google2FA;

class TwoFactorService
{
    private Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function qrCodeInline(User $user, string $secret): string
    {
        return $this->google2fa->getQRCodeInline(
            config('app.name', 'Bóveda'),
            $user->email,
            $secret,
        );
    }

    public function verify(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey($secret, $code);
    }
}