<?php

namespace App\Rules;

use App\Services\ShippingMethodService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Translation\PotentiallyTranslatedString;

class ShippingHashExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // $found = app(ShippingMethodService::class)->getShippingMethod($value);
        // if (! $found) {
        //     $fail('The selected shipping method is invalid or has expired!');
        // }

        if (Cache::missing("shipping-method-$value")) {
            $fail('The selected shipping method is invalid or has expired!');
        }
    }
}
