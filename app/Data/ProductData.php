<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Product;
use Illuminate\Support\Number;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ProductData extends Data
{
    #[Computed]
    public string $formattedPrice;

    public function __construct(
        public string $name,
        public string $slug,
        public string $sku,
        public ?string $description,
        public int $stock,
        public float $price,
        public int $weight,
        public ?string $coverUrl,
        public string $tags,
        public Optional|array $gallery,
    ) {
        $this->formattedPrice = Number::currency($this->price, 'IDR');
    }

    public static function fromModel(Product $product, bool $gallery = false): self
    {
        return new self(
            name: $product->name,
            slug: $product->slug,
            sku: $product->sku,
            description: $product->description,
            stock: $product->stock,
            price: floatval($product->price),
            weight: $product->weight,
            coverUrl: $product->getFirstMediaUrl('cover'),
            tags: $product->tags()->where('type', 'category')->pluck('name')->implode(', '),
            gallery: $gallery ? $product->getMedia('gallery')->map(fn ($item) => $item->getUrl())->toArray() : new Optional,
        );
    }
}
