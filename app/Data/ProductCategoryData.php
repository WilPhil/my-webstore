<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Tag;
use Spatie\LaravelData\Data;

class ProductCategoryData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public int $productCount
    ) {}

    public static function fromModel(Tag $tag): self
    {
        return new self(
            id: $tag->id,
            name: (string) $tag->name,
            slug: (string) $tag->slug,
            productCount: $tag->products_count,
        );
    }
}
