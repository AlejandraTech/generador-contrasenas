<?php

namespace App\Http\Controllers;

use App\Models\VaultEntry;
use App\Services\ShareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    public function __construct(private readonly ShareService $share) {}

    public function store(Request $request, VaultEntry $entry)
    {
        if ($entry->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'ttl_minutes' => ['required', 'integer', 'min:1', 'max:10080'],
            'max_views' => ['required', 'integer', 'min:1', 'max:100'],
            'recipient_password' => ['nullable', 'string', 'max:255'],
        ]);

        $link = $this->share->create(
            entry: $entry,
            maxViews: $validated['max_views'],
            ttlMinutes: $validated['ttl_minutes'],
            recipientPassword: $validated['recipient_password'] ?? null,
        );

        return redirect()->route('vault.show', $entry)->with('status', 'Link creado: '.route('share.show', $link->token));
    }

    public function show(string $token)
    {
        $link = $this->share->findByToken($token);

        if ($link === null || $link->isConsumed()) {
            return view('share.expired');
        }

        return view('share.show', ['link' => $link, 'token' => $token]);
    }

    public function reveal(Request $request, string $token)
    {
        $link = $this->share->findByToken($token);

        if ($link === null || $link->isConsumed()) {
            return response()->view('share.expired', [], 410);
        }

        $request->validate([
            'recipient_password' => ['nullable', 'string', 'max:255'],
        ]);

        $password = $this->share->consume($link, $request->input('recipient_password'));

        if ($password === null) {
            return back()->withErrors(['recipient_password' => 'Contraseña incorrecta o link no válido.']);
        }

        return view('share.reveal', [
            'password' => $password,
            'link' => $link,
        ]);
    }

    public function destroy(Request $request, VaultEntry $entry, $linkId)
    {
        if ($entry->user_id !== Auth::id()) {
            abort(403);
        }
        $link = $entry->shareLinks()->findOrFail($linkId);
        $link->delete();

        return redirect()->route('vault.show', $entry)->with('status', 'Link de compartir revocado.');
    }
}