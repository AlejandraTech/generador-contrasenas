<?php

namespace Tests\Unit;

use App\Services\PasswordGenerator;
use App\Services\StrengthAnalyzer;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PasswordGeneratorTest extends TestCase
{
    #[Test]
    public function random_generates_requested_length(): void
    {
        $generator = new PasswordGenerator();

        $this->assertSame(20, strlen($generator->generateRandom(20)));
        $this->assertSame(1, strlen($generator->generateRandom(1)));
    }

    #[Test]
    public function random_uses_only_selected_charsets(): void
    {
        $generator = new PasswordGenerator();
        $password = $generator->generateRandom(200, special: false, numbers: false, uppercase: false, lowercase: true);

        $this->assertMatchesRegularExpression('/^[a-z]+$/', $password);
    }

    #[Test]
    public function random_empty_charset_falls_back_to_lowercase(): void
    {
        $generator = new PasswordGenerator();
        $password = $generator->generateRandom(50, special: false, numbers: false, uppercase: false, lowercase: false);

        $this->assertMatchesRegularExpression('/^[a-z]+$/', $password);
    }

    #[Test]
    public function passphrase_returns_expected_word_count(): void
    {
        $generator = new PasswordGenerator();
        $passphrase = $generator->generatePassphrase(words: 4, separator: '-', capitalize: false);

        $this->assertCount(4, explode('-', $passphrase));
    }

    #[Test]
    public function passphrase_with_custom_wordlist(): void
    {
        $generator = new PasswordGenerator();
        $passphrase = $generator->generatePassphrase(words: 3, separator: ' ', wordList: ['alfa', 'bravo', 'charlie']);

        $words = explode(' ', $passphrase);
        $this->assertCount(3, $words);
        foreach ($words as $w) {
            $this->assertContains(strtolower($w), ['alfa', 'bravo', 'charlie']);
        }
    }

    #[Test]
    public function random_is_cryptographically_varied(): void
    {
        $generator = new PasswordGenerator();
        $samples = [];
        for ($i = 0; $i < 20; $i++) {
            $samples[] = $generator->generateRandom(32);
        }
        $samples = array_unique($samples);

        // 20 muestras de 32 chars deben ser (prácticamente) todas únicas.
        $this->assertGreaterThan(18, count($samples));
    }
}