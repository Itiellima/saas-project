<?php

namespace App\Rules;

use App\Support\BrDocument;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfCnpj implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! BrDocument::isValid((string) $value)) {
            $fail('O CPF ou CNPJ informado não é válido.');
        }
    }
}
