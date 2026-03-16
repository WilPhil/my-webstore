<?php

use App\Data\ProductData;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function featuredProducts()
    {
        return ProductData::collect(Product::query()->inRandomOrder()->limit(3)->get());
    }

    #[Computed]
    public function latestProducts()
    {
        return ProductData::collect(Product::query()->latest()->limit(3)->get());
    }
};
?>

<div class="container mx-auto max-w-[85rem] w-full">
    <div class="mt-10">
        <x-product-sections title="Feature Product" :url="route('product-catalog')" :products="$this->featuredProducts" />
        <x-featured-icon />
        <x-product-sections title="Latest Products" :url="route('product-catalog')" :products="$this->latestProducts" />
    </div>
</div>
