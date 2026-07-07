<?php

namespace App\Support;

class UsZip
{
    public static function normalize(?string $zip): ?string
    {
        if ($zip === null) {
            return null;
        }

        $digits = preg_replace('/\D/', '', trim($zip));

        if ($digits === null || strlen($digits) < 5) {
            return null;
        }

        return substr($digits, 0, 5);
    }

    public static function isValid(?string $zip): bool
    {
        return self::normalize($zip) !== null;
    }
}
