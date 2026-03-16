<?php

namespace App\Actions;

use App\Contract\CartServiceInterface;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class ValidateProductStock
{
    use AsAction;

    public function __construct(
        public CartServiceInterface $cart
    ) {}

    public function handle()
    {
        $insufficient = [];

        foreach ($this->cart->getAllItem()->items as $item) {
            /** @var ProductData $product */
            $product = $item->product();

            if (! $product || $product->stock < $item->quantity) {
                $insufficient[] = [
                    'name' => $product->name ?? 'Unknown',
                    'sku' => $product->sku,
                    'requested' => $item->quantity,
                    'available' => $product?->stock ?? 0,
                ];
            }
        }

        if ($insufficient) {
            throw ValidationException::withMessages([
                'cart' => 'Some product stocks are insufficient.',
                'details' => $insufficient,
            ]);
        }
    }
}
