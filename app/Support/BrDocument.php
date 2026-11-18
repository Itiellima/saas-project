<?php

namespace App\Support;

/**
 * CPF e CNPJ: limpeza, validação dos dígitos verificadores e formatação.
 */
class BrDocument
{
    public static function digits(?string $value): string
    {
        return preg_replace('/\D/', '', (string) $value);
    }

    public static function isValid(?string $value): bool
    {
        $digits = self::digits($value);

        return match (strlen($digits)) {
            11 => self::isValidCpf($digits),
            14 => self::isValidCnpj($digits),
            default => false,
        };
    }

    public static function type(?string $value): ?string
    {
        return match (strlen(self::digits($value))) {
            11 => 'CPF',
            14 => 'CNPJ',
            default => null,
        };
    }

    public static function format(?string $value): ?string
    {
        $d = self::digits($value);

        return match (strlen($d)) {
            11 => sprintf('%s.%s.%s-%s', substr($d, 0, 3), substr($d, 3, 3), substr($d, 6, 3), substr($d, 9)),
            14 => sprintf('%s.%s.%s/%s-%s', substr($d, 0, 2), substr($d, 2, 3), substr($d, 5, 3), substr($d, 8, 4), substr($d, 12)),
            default => $value ?: null,
        };
    }

    private static function isValidCpf(string $cpf): bool
    {
        // 000.000.000-00, 111.111.111-11... passam na conta, mas não existem
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($length = 9; $length < 11; $length++) {
            $sum = 0;
            for ($i = 0; $i < $length; $i++) {
                $sum += (int) $cpf[$i] * ($length + 1 - $i);
            }

            $check = ((10 * $sum) % 11) % 10;

            if ((int) $cpf[$length] !== $check) {
                return false;
            }
        }

        return true;
    }

    private static function isValidCnpj(string $cnpj): bool
    {
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        foreach ([12, 13] as $length) {
            $weights = $length === 12
                ? [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]
                : [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

            $sum = 0;
            for ($i = 0; $i < $length; $i++) {
                $sum += (int) $cnpj[$i] * $weights[$i];
            }

            $rest = $sum % 11;
            $check = $rest < 2 ? 0 : 11 - $rest;

            if ((int) $cnpj[$length] !== $check) {
                return false;
            }
        }

        return true;
    }
}
