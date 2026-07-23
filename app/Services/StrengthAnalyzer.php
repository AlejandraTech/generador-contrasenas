<?php

namespace App\Services;

class StrengthAnalyzer
{
    public const CHARSET_LOWER = 26;
    public const CHARSET_UPPER = 26;
    public const CHARSET_DIGITS = 10;
    public const CHARSET_SPECIAL = 26;

    public const RATING_VERY_WEAK = 'very_weak';
    public const RATING_WEAK = 'weak';
    public const RATING_FAIR = 'fair';
    public const RATING_STRONG = 'strong';
    public const RATING_VERY_STRONG = 'very_strong';

    /**
     * Entropía en bits: H = L * log2(N).
     */
    public function entropyBits(string $password): float
    {
        $pool = 0;
        $hasLower = preg_match('/[a-z]/', $password);
        $hasUpper = preg_match('/[A-Z]/', $password);
        $hasDigit = preg_match('/[0-9]/', $password);
        $hasSpecial = preg_match('/[^a-zA-Z0-9]/', $password);

        if ($hasLower) {
            $pool += self::CHARSET_LOWER;
        }
        if ($hasUpper) {
            $pool += self::CHARSET_UPPER;
        }
        if ($hasDigit) {
            $pool += self::CHARSET_DIGITS;
        }
        if ($hasSpecial) {
            $pool += self::CHARSET_SPECIAL;
        }

        if ($pool === 0) {
            return 0.0;
        }

        return strlen($password) * log($pool, 2);
    }

    /**
     * Tiempo estimado de cracking offline a 10^11 hashes/seg (GPU rig típico).
     */
    public function crackTimeHuman(float $entropyBits): string
    {
        $guesses = pow(2, $entropyBits);
        $seconds = $guesses / 100_000_000_000;

        return $this->humanizeSeconds((float) $seconds);
    }

    public function rating(float $entropyBits): string
    {
        return match (true) {
            $entropyBits < 28 => self::RATING_VERY_WEAK,
            $entropyBits < 36 => self::RATING_WEAK,
            $entropyBits < 60 => self::RATING_FAIR,
            $entropyBits < 128 => self::RATING_STRONG,
            default => self::RATING_VERY_STRONG,
        };
    }

    public function analyze(string $password): array
    {
        $entropy = $this->entropyBits($password);

        return [
            'entropy' => round($entropy, 2),
            'crack_time' => $this->crackTimeHuman($entropy),
            'rating' => $this->rating($entropy),
            'length' => strlen($password),
        ];
    }

    private function humanizeSeconds(float $seconds): string
    {
        if ($seconds < 1) {
            return 'instantáneo';
        }
        if (! is_finite($seconds)) {
            return 'más que la edad del universo';
        }
        if ($seconds < 60) {
            return round($seconds).' segundos';
        }
        if ($seconds < 3600) {
            return round($seconds / 60).' minutos';
        }
        if ($seconds < 86400) {
            return round($seconds / 3600).' horas';
        }
        if ($seconds < 2_592_000) {
            return round($seconds / 86400).' días';
        }
        if ($seconds < 31_536_000) {
            return round($seconds / 2_592_000).' meses';
        }
        if ($seconds < 1_000_000_000) {
            return round($seconds / 31_536_000).' años';
        }

        $billions = $seconds / 1_000_000_000;

        return round($billions).' mil millones de años';
    }
}