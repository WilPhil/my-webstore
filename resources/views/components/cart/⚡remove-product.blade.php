<?php

use App\Contract\CartServiceInterface;
use Livewire\Component;
use App\Data\ProductData;

new class extends Component {
    public string $name;
    public string $sku;

    public function mount(ProductData $product)
    {
        $this->name = $product->name;
        $this->sku = $product->sku;
    }

    public function removeProduct(CartServiceInterface $cart)
    {
        $cart->removeItem($this->sku);

        session()->flash('success', "Product {$this->name} removed.");
        $this->dispatch('cart-updated');
        $this->redirectRoute('cart-list');
    }
};
?>

<div>
    <button wire:click='removeProduct' type="button"
        class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg cursor-pointer gap-x-2 hover:bg-red-700 focus:outline-hidden focus:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-trash2-icon lucide-trash-2">
            <path d="M10 11v6" />
            <path d="M14 11v6" />
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
            <path d="M3 6h18" />
            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
        </svg>
    </button>
</div>
