<?php

use App\Actions\ValidateProductStock;
use App\Contract\CartServiceInterface;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public string $subTotal;

    public string $shipping = 'IDR 0.00';

    public string $total;

    public function mount(CartServiceInterface $cart)
    {
        $this->subTotal = $cart->getAllItem()->totalPriceFormatted;
        $this->total = $this->subTotal;
    }

    public function checkout()
    {
        try {
            ValidateProductStock::run();
            $this->redirectRoute('checkout');
        } catch (ValidationException $e) {
            session()->flash('error', $e->getMessage());
            $this->redirectRoute('cart-list');
        }
    }

    #[On('cart-updated')]
    public function updateTotal(CartServiceInterface $cart)
    {
        $this->subTotal = $cart->getAllItem()->totalPriceFormatted;
        $this->total = $this->subTotal;
    }

    #[Computed]
    public function items()
    {
        $cart = app(CartServiceInterface::class);

        return $cart->getAllItem()->items->toCollection();
    }
};
