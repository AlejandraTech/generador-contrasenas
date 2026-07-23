<?php

namespace App\Services;

class PasswordGenerator
{
    public const TYPE_RANDOM = 'random';
    public const TYPE_PASSPHRASE = 'passphrase';

    private const LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
    private const UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const NUMBERS = '0123456789';
    private const SPECIAL = '!@#$%^&*()_+-=[]{}|;:,.<>?';

    public function generateRandom(
        int $length,
        bool $special = true,
        bool $numbers = true,
        bool $uppercase = true,
        bool $lowercase = true,
    ): string {
        $pool = '';
        if ($lowercase) {
            $pool .= self::LOWERCASE;
        }
        if ($uppercase) {
            $pool .= self::UPPERCASE;
        }
        if ($numbers) {
            $pool .= self::NUMBERS;
        }
        if ($special) {
            $pool .= self::SPECIAL;
        }

        if ($pool === '') {
            $pool = self::LOWERCASE;
        }

        $poolSize = strlen($pool);
        $bytes = random_bytes($length);

        $out = '';
        for ($i = 0; $i < $length; $i++) {
            $out .= $pool[ord($bytes[$i]) % $poolSize];
        }

        return $out;
    }

    /**
     * @param  list<string>|null  $wordList
     */
    public function generatePassphrase(int $words, string $separator = '-', ?array $wordList = null, bool $capitalize = true): string
    {
        $list = $wordList ?? require __DIR__.'/wordlist_es.php';
        $count = count($list);
        $picked = [];
        for ($i = 0; $i < $words; $i++) {
            $idx = ord(random_bytes(1)[0]) % $count;
            $word = $list[$idx];
            $picked[] = $capitalize ? ucfirst($word) : $word;
        }

        return implode($separator, $picked);
    }
}