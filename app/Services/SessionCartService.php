<?php

declare(strict_types=1);

namespace App\Services;

use App\Contract\CartServiceInterface;
use App\Data\CartData;
use App\Data\CartItemData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Spatie\LaravelData\DataCollection;

class SessionCartService implements CartServiceInterface
{
    protected string $session_key = 'cart';

    protected function loadSession(): DataCollection
    {
        $raw = Session::get($this->session_key, []);

        return new DataCollection(CartItemData::class, $raw);
    }

    /** @param Collection<int, CartItemData> $items */
    protected function saveSession(Collection $items): void
    {
        Session::put($this->session_key, $items->values()->all());
    }

    public function addOrUpdateItem(CartItemData $item): void
    {
        // Get the cart data
        $collection = $this->loadSession()->toCollection();
        $updated = false;

        $cart = $collection->map(function (CartItemData $existingItem) use ($item, &$updated) {
            // Check if the product already exist or no
            if ($existingItem->sku == $item->sku) {
                // Change the value to true so it will not add a copy of the existing project in a new row
                $updated = true;

                // Change the content data (quantity, weight, price)
                return $item;
            }

            // Keep the data
            return $existingItem;
        })->values()->collect();

        // If not using '&' it will add the same existing product in the new 'cart' row (Keyboard 1x => Keyboard 2x, Keyboard 1x)
        if (! $updated) {
            $cart->push($item);
        }

        // Save the cart data to the session
        $this->saveSession($cart);
    }

    public function removeItem(string $sku): void
    {
        $cart = $this->loadSession()->toCollection()
            ->reject(fn (CartItemData $i) => $i->sku === $sku)
            ->values()->collect();

        $this->saveSession($cart);
    }

    public function getItemBySku(string $sku): ?CartItemData
    {
        return $this->loadSession()->toCollection()->first(fn (CartItemData $item) => $item->sku == $sku);
    }

    public function getAllItem(): CartData
    {
        return new CartData($this->loadSession());
    }
}
