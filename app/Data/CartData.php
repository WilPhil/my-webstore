<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Number;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class CartData extends Data
{
    #[Computed]
    public float $totalPrice;

    public int $totalQuantity;

    public int $totalWeight;

    public string $totalPriceFormatted;

    public function __construct(
        #[DataCollectionOf(CartItemData::class)]
        public DataCollection $items,
    ) {
        $items = $items->toCollection();

        $this->totalPrice = $items->sum(fn ($item) => $item->price * $item->quantity);
        $this->totalQuantity = $items->sum(fn ($item) => $item->quantity);
        $this->totalWeight = $items->sum(fn ($item) => $item->weight * $item->quantity);
        $this->totalPriceFormatted = Number::currency($this->totalPrice, 'IDR');
    }
}
