<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TwoFactorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_without_2fa_lands_on_vault_after_login(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secret123')]);

        $response = $this->post(route('login'), ['email' => $user->email, 'password' => 'secret123']);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function user_with_2fa_is_challenged_after_login(): void
    {
        $service = app(TwoFactorService::class);
        $secret = $service->generateSecretKey();
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
            'two_factor_secret' => encrypt($secret),
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->post(route('login'), ['email' => $user->email, 'password' => 'secret123']);

        $response->assertRedirect(route('twofactor.challenge'));
        $this->assertGuest();
    }

    #[Test]
    public function valid_2fa_code_completes_login(): void
    {
        $service = app(TwoFactorService::class);
        $secret = $service->generateSecretKey();
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
            'two_factor_secret' => encrypt($secret),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->post(route('login'), ['email' => $user->email, 'password' => 'secret123']);

        $code = (new \PragmaRX\Google2FA\Google2FA())->getCurrentOtp($secret);
        $response = $this->post(route('twofactor.verify'), ['code' => $code]);

        $response->assertRedirect(route('vault.index'));
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function wrong_2fa_code_is_rejected(): void
    {
        $service = app(TwoFactorService::class);
        $secret = $service->generateSecretKey();
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
            'two_factor_secret' => encrypt($secret),
            'two_factor_confirmed_at' => now(),
        ]);

        $this->post(route('login'), ['email' => $user->email, 'password' => 'secret123']);
        $response = $this->post(route('twofactor.verify'), ['code' => '000000']);

        $response->assertSessionHasErrors(['code']);
        $this->assertGuest();
    }
}