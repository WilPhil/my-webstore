<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Number;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class SalesOrderItemData extends Data
{
    #[Computed]
    public string $price_formatted;

    #[Computed]
    public string $total_formatted;

    public function __construct(
        public string $name,
        public string $slug,
        public string $sku,
        public string $tags,
        public ?string $description,
        public ?string $cover_url,
        public int $quantity,
        public int $weight,
        public float $price,
        public float $total,
    ) {
        $this->price_formatted = Number::currency($price);
        $this->total_formatted = Number::currency($total);
    }
}
