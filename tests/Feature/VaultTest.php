<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VaultEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VaultTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_user_sees_empty_vault(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('vault.index'));

        $response->assertOk()->assertSee('Tu bóveda está vacía');
    }

    #[Test]
    public function user_can_generate_random_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('vault.store'), [
            'type' => 'random',
            'length' => 24,
            'include_special' => true,
            'include_numbers' => true,
            'include_uppercase' => true,
            'include_lowercase' => true,
            'title' => 'Mi banco',
        ]);

        $entry = VaultEntry::first();
        $response->assertRedirect(route('vault.show', $entry));
        $this->assertSame(24, strlen($entry->getDecryptedValue()));
        $this->assertSame('Mi banco', $entry->title);
    }

    #[Test]
    public function user_can_generate_passphrase(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('vault.store'), [
            'type' => 'passphrase',
            'words' => 5,
            'separator' => '-',
        ]);

        $entry = VaultEntry::first();
        $this->assertSame('passphrase', $entry->type);
        $this->assertCount(5, explode('-', $entry->getDecryptedValue()));
    }

    #[Test]
    public function user_cannot_view_other_users_entries(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $entry = VaultEntry::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($intruder)->get(route('vault.show', $entry));

        $response->assertForbidden();
    }

    #[Test]
    public function user_can_delete_own_entry(): void
    {
        $user = User::factory()->create();
        $entry = VaultEntry::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->from(route('vault.show', $entry))
            ->delete(route('vault.destroy', $entry));

        $this->assertDatabaseMissing('vault_entries', ['id' => $entry->id]);
    }

    #[Test]
    public function preview_returns_json(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('vault.preview'), [
            'type' => 'random',
            'length' => 16,
            'include_lowercase' => true,
        ]);

        $response->assertOk()->assertJsonStructure(['password', 'entropy', 'crack_time', 'rating']);
    }

    #[Test]
    public function tags_are_synced_on_store(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('vault.store'), [
            'type' => 'random',
            'length' => 12,
            'tags' => ['trabajo', 'banking'],
        ]);

        $entry = VaultEntry::first();
        $this->assertCount(2, $entry->tags);
        $this->assertDatabaseHas('tags', ['user_id' => $user->id, 'name' => 'trabajo']);
    }
}