<?php

namespace Tests\Unit;

use App\Services\StrengthAnalyzer;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StrengthAnalyzerTest extends TestCase
{
    private StrengthAnalyzer $analyzer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->analyzer = new StrengthAnalyzer();
    }

    #[Test]
    public function entropy_of_lowercase_only_password_is_low(): void
    {
        $bits = $this->analyzer->entropyBits('abcdefghij');

        // 10 chars * log2(26) ≈ 47 bits
        $this->assertEqualsWithDelta(47.0, $bits, 1.0);
    }

    #[Test]
    public function entropy_grows_with_charset_diversity(): void
    {
        $lower = $this->analyzer->entropyBits('abcdefghij');
        $mixed = $this->analyzer->entropyBits('Aa1!Bb2#Cc');

        $this->assertGreaterThan($lower, $mixed);
    }

    #[Test]
    public function very_long_random_password_is_very_strong(): void
    {
        $analysis = $this->analyzer->analyze(bin2hex(random_bytes(32)));

        $this->assertGreaterThanOrEqual(128, $analysis['entropy']);
        $this->assertSame(StrengthAnalyzer::RATING_VERY_STRONG, $analysis['rating']);
    }

    #[Test]
    public function short_single_charset_is_very_weak(): void
    {
        $analysis = $this->analyzer->analyze('abc');

        $this->assertSame(StrengthAnalyzer::RATING_VERY_WEAK, $analysis['rating']);
        $this->assertSame('instantáneo', $analysis['crack_time']);
    }

    #[Test]
    public function crack_time_humanizes_large_values(): void
    {
        $human = $this->analyzer->crackTimeHuman(128);

        $this->assertStringContainsString('años', $human);
    }
}