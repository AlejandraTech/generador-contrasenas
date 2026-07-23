<?php

namespace App\Services;

use App\Models\ShareLink;
use App\Models\VaultEntry;
use Illuminate\Support\Str;

class ShareService
{
    public function create(VaultEntry $entry, int $maxViews = 1, int $ttlMinutes = 15, ?string $recipientPassword = null): ShareLink
    {
        $payload = [
            'vault_entry_id' => $entry->id,
            'token' => Str::random(32),
            'max_views' => max(1, $maxViews),
            'views' => 0,
            'expires_at' => now()->addMinutes(max(1, $ttlMinutes)),
        ];

        if ($recipientPassword !== null && $recipientPassword !== '') {
            $payload['recipient_hash'] = hash('sha256', $recipientPassword);
        }

        return ShareLink::create($payload);
    }

    /**
     * Devuelve el valor de la entrada si el link es válido y coincide la
     * contraseña opcional. Incrementa el contador de vistas.
     */
    public function consume(ShareLink $link, ?string $recipientPassword = null): ?string
    {
        if ($link->isConsumed()) {
            return null;
        }

        if ($link->recipient_hash !== null) {
            if ($recipientPassword === null || hash('sha256', $recipientPassword) !== $link->recipient_hash) {
                return null;
            }
        }

        $link->forceFill([
            'views' => $link->views + 1,
            'viewed_at' => $link->viewed_at ?? now(),
        ])->save();

        return $link->vaultEntry->getDecryptedValue();
    }

    public function findByToken(string $token): ?ShareLink
    {
        return ShareLink::where('token', $token)->first();
    }
}