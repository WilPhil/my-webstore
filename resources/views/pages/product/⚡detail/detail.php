<?php

use App\Data\ProductData;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public Product $productModel;

    public function mount(Product $product)
    {
        $this->productModel = $product;
    }

    #[Computed]
    public function product()
    {

        return ProductData::fromModel($this->productModel, true);
    }
};
