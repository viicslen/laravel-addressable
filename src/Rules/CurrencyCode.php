<?php

namespace ViicSlen\Addressable\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use ViicSlen\Addressable\Facades\Addressable;

class CurrencyCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Addressable::currency($value)) {
            $fail('The :attribute must be a valid currency code.');
        }
    }
}
