<?php

use App\Contract\CartServiceInterface;
use Illuminate\Support\Number;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public array $user_data = [
        'full_name' => null,
        'email_address' => null,
        'phone_number' => null,
        'shipping_address' => null,
    ];

    public array $cart_summaries = [
        'weight_total' => 0,
        'sub_total' => 0,
        'sub_total_formatted' => '-',
        'shipping_total' => 0,
        'shipping_total_formatted' => '-',
        'grand_total' => 0,
        'grand_total_formatted' => '-',
    ];

    public function mount()
    {
        $cart = app(CartServiceInterface::class);
        $shipping_total = 0;
        $grand_total = $cart->getAllItem()->totalPrice + $shipping_total;

        $this->cart_summaries = [
            'weight_total' => $cart->getAllItem()->totalWeight,
            'sub_total' => $cart->getAllItem()->totalPrice,
            'sub_total_formatted' => $cart->getAllItem()->totalPriceFormatted,
            'shipping_total' => 0,
            'shipping_total_formatted' => Number::currency($shipping_total),
            'grand_total' => $grand_total,
            'grand_total_formatted' => Number::currency($grand_total),
        ];
    }

    public function rules()
    {
        return [
            'user_data.full_name' => 'required|string|min:3|max:255',
            'user_data.email_address' => 'required|email:dns',
            'user_data.phone_number' => 'required|min:10|max:13',
            'user_data.shipping_address' => 'required|string|min:10|max:255',
        ];
    }

    public function placeAnOrder()
    {
        $this->validate();

        dd($this->user_data);
    }

    #[Computed()]
    public function items()
    {
        return app(CartServiceInterface::class)->getAllItem()->items;
    }
};
