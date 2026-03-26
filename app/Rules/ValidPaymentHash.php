<?php

namespace App\Rules;

use App\Services\PaymentMethodQueryService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidPaymentHash implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $found = app(PaymentMethodQueryService::class)->getPaymentMethodByHash($value);

        if (! $found) {
            $fail('Payment Method is not valid.');
        }
    }
}
