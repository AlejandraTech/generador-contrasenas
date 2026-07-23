<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VaultEntry;
use App\Services\ShareService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShareTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function link_reveals_password_once_then_is_consumed(): void
    {
        $user = User::factory()->create();
        $entry = VaultEntry::factory()->create(['user_id' => $user->id, 'value' => encrypt('super-secret')]);

        $share = app(ShareService::class)->create($entry, maxViews: 1, ttlMinutes: 60);

        // Primera visita: página de reveal.
        $page = $this->get(route('share.show', $share->token));
        $page->assertOk()->assertSee('Revelar contraseña');

        // Revelar.
        $reveal = $this->post(route('share.reveal', $share->token));
        $reveal->assertOk()->assertSee('super-secret');

        // Link consumido: la siguiente visita muestra expirado.
        $after = $this->get(route('share.show', $share->token));
        $after->assertOk()->assertSee('autodestruida');
    }

    #[Test]
    public function link_with_recipient_password_requires_it(): void
    {
        $user = User::factory()->create();
        $entry = VaultEntry::factory()->create(['user_id' => $user->id, 'value' => encrypt('top-secret')]);

        $share = app(ShareService::class)->create($entry, maxViews: 1, ttlMinutes: 60, recipientPassword: 'shared-pass');

        $wrong = $this->post(route('share.reveal', $share->token), ['recipient_password' => 'wrong']);
        $wrong->assertSessionHasErrors(['recipient_password']);

        $right = $this->post(route('share.reveal', $share->token), ['recipient_password' => 'shared-pass']);
        $right->assertOk()->assertSee('top-secret');
    }

    #[Test]
    public function expired_link_shows_expired_view(): void
    {
        $user = User::factory()->create();
        $entry = VaultEntry::factory()->create(['user_id' => $user->id]);

        $share = app(ShareService::class)->create($entry, maxViews: 1, ttlMinutes: 1);
        $share->forceFill(['expires_at' => now()->subMinute()])->save();

        $this->get(route('share.show', $share->token))->assertOk()->assertSee('autodestruida');
    }

    #[Test]
    public function owner_can_revoke_link(): void
    {
        $user = User::factory()->create();
        $entry = VaultEntry::factory()->create(['user_id' => $user->id]);
        $share = app(ShareService::class)->create($entry);

        $this->actingAs($user)
            ->from(route('vault.show', $entry))
            ->delete(route('share.destroy', [$entry, $share]));

        $this->assertDatabaseMissing('share_links', ['id' => $share->id]);
    }
}