<?php

namespace App\Support;

class Cnpj
{
    public static function digits(?string $value): string
    {
        return preg_replace('/\D/', '', (string) $value);
    }

    /** Confere os dois dígitos verificadores. Recebe com ou sem máscara. */
    public static function isValid(?string $value): bool
    {
        $c = self::digits($value);
        if (strlen($c) !== 14 || preg_match('/^(\d)\1{13}$/', $c)) {
            return false;
        }

        foreach ([12, 13] as $len) {
            $weights = $len === 12 ? [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2] : [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
            $sum = 0;
            foreach ($weights as $i => $w) {
                $sum += (int) $c[$i] * $w;
            }
            $dv = $sum % 11 < 2 ? 0 : 11 - $sum % 11;
            if ((int) $c[$len] !== $dv) {
                return false;
            }
        }

        return true;
    }

    public static function format(?string $value): ?string
    {
        $c = self::digits($value);

        return strlen($c) === 14
            ? substr($c, 0, 2).'.'.substr($c, 2, 3).'.'.substr($c, 5, 3).'/'.substr($c, 8, 4).'-'.substr($c, 12, 2)
            : ($value ?: null);
    }
}
