<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VaultEntry>
 */
class VaultEntryFactory extends Factory
{
    public function definition(): array
    {
        $plain = \App\Services\PasswordGenerator::class && false
            ? 'test-password'
            : substr(bin2hex(random_bytes(8)), 0, 16);

        return [
            'user_id' => User::factory(),
            'category_id' => null,
            'value' => encrypt($plain),
            'title' => fake()->words(2, true),
            'site' => fake()->domainName(),
            'username' => fake()->userName(),
            'notes' => null,
            'type' => 'random',
            'length' => 16,
            'include_special' => true,
            'include_numbers' => true,
            'include_uppercase' => true,
            'include_lowercase' => true,
            'entropy_bits' => 95,
        ];
    }
}