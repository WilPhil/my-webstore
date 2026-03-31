<?php

use App\Contract\CartServiceInterface;
use App\Data\CheckoutData;
use App\Data\CustomerData;
use App\Data\RegionData;
use App\Data\ShippingData;
use App\Rules\ShippingHashExists;
use App\Rules\ValidPaymentHash;
use App\Services\CheckoutService;
use App\Services\LocationQueryService;
use App\Services\PaymentMethodQueryService;
use App\Services\ShippingMethodService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Number;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\LaravelData\DataCollection;

new class extends Component
{
    public array $user_data = [
        'full_name' => null,
        'email_address' => null,
        'phone_number' => null,
        'shipping_address' => null,
        'shipping_location_code' => null,
        'shipping_method_hash' => null,
        'payment_method_hash' => null,
    ];

    public array $location_data = [
        'keyword' => null,
        'selected_location_code' => null,
    ];

    public array $shipping_data = [
        'shipping_method_hash' => null,
    ];

    public array $payment_data = [
        'payment_method_hash' => null,
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

    #[Computed()]
    public function cart()
    {
        return app(CartServiceInterface::class)->getAllItem();
    }

    public function locationQueryService()
    {
        return app(LocationQueryService::class);
    }

    public function shippingMethodService()
    {
        return app(ShippingMethodService::class);
    }

    public function paymentMethodQueryService()
    {
        return app(PaymentMethodQueryService::class);
    }

    public function mount()
    {
        if (Gate::check('empty_cart')) {
            $this->redirectRoute('cart-list');
            session()->flash('error', 'Cart can not be empty.');
        }
        $this->calculateTotals();
    }

    public function rules()
    {
        return [
            'user_data.full_name' => 'required|string|min:3|max:255',
            'user_data.email_address' => 'required|email:dns',
            'user_data.phone_number' => 'required|min:10|max:13',
            'user_data.shipping_address' => 'required|string|min:10|max:255',
            'user_data.shipping_location_code' => 'required|exists:regions,code',
            'user_data.shipping_method_hash' => ['required', new ShippingHashExists],
            'user_data.payment_method_hash' => ['required', new ValidPaymentHash],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'user_data.full_name' => 'full name',
            'user_data.email_address' => 'email address',
            'user_data.phone_number' => 'phone number',
            'user_data.shipping_address' => 'shipping address',
            'user_data.shipping_location_code' => 'location',
            'user_data.shipping_method_hash' => 'shipping method',
            'user_data.payment_method_hash' => 'payment method',
        ];
    }

    public function updatedLocationDataSelectedLocationCode($value)
    {
        data_set($this->user_data, 'shipping_location_code', $value);
    }

    public function updatedShippingDataShippingMethodHash($value)
    {
        data_set($this->user_data, 'shipping_method_hash', $value);
        $this->dispatch('update-shipping-method');
    }

    public function updatedPaymentDataPaymentMethodHash($value)
    {
        data_set($this->user_data, 'payment_method_hash', $value);
    }

    #[On('update-shipping-method')]
    public function updateShipping()
    {
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $shipping_total = $this->shippingMethod?->cost ?? 0;
        $grand_total = $this->cart->totalPrice + $shipping_total;

        $this->cart_summaries = [
            'weight_total' => $this->cart->totalWeight,
            'sub_total' => $this->cart->totalPrice,
            'sub_total_formatted' => $this->cart->totalPriceFormatted,
            'shipping_total' => $shipping_total,
            'shipping_total_formatted' => Number::currency($shipping_total),
            'grand_total' => $grand_total,
            'grand_total_formatted' => Number::currency($grand_total),
        ];
    }

    public function placeAnOrder(CartServiceInterface $cart)
    {
        $validated = $this->validate();
        $customer_data = CustomerData::from(data_get($validated, 'user_data'));
        $shipping_method = $this->shippingMethodService()->getShippingMethod(data_get($validated, 'user_data.shipping_method_hash'));
        $payment_method = $this->paymentMethodQueryService()->getPaymentMethodByHash(data_get($validated, 'user_data.payment_method_hash'));

        $checkout_data = CheckoutData::from([
            'customer' => $customer_data,
            'shipping_address' => data_get($validated, 'user_data.shipping_address'),
            'origin' => $shipping_method->origin,
            'destination' => $shipping_method->destination,
            'cart' => $this->cart,
            'shipping' => $shipping_method,
            'payment' => $payment_method,
        ]);
        $sales_order = app(CheckoutService::class)->makeAnOrder($checkout_data);
        $cart->clearCart();

        $this->redirectRoute('order-confirmed', ['items' => $sales_order->trx_id]);
    }

    #[Computed()]
    public function items()
    {
        return $this->cart->items;
    }

    #[Computed()]
    public function locations(): DataCollection
    {
        $data = [];
        $keyword = data_get($this->location_data, 'keyword');

        if ($keyword == null) {
            $data = [];

            return RegionData::collect($data, DataCollection::class);
        }

        $data = $this->locationQueryService()->searchLocationByKeyword($keyword);

        return RegionData::collect($data, DataCollection::class);
    }

    #[Computed()]
    public function location(): ?RegionData
    {
        if (! $this->location_data['selected_location_code']) {
            return null;
        }

        $code = data_get($this->location_data, 'selected_location_code');
        $location = $this->locationQueryService()->searchLocationByCode($code);

        return $location;
    }

    /** @return DataCollection<ShippingData> */
    #[Computed()]
    public function shippingMethods(): DataCollection|Collection
    {
        if (data_get($this->location_data, 'selected_location_code') == null) {
            return new DataCollection(ShippingData::class, []);
        }

        $shipping_origin_code = config('shipping.shipping_origin_code');

        return $this->shippingMethodService()->getShippingMethods(
            $this->locationQueryService()->searchLocationByCode($shipping_origin_code),
            $this->locationQueryService()->searchLocationByCode(data_get($this->location_data, 'selected_location_code')),
            $this->cart
        )
            ->toCollection()
            ->groupBy('service');
    }

    #[Computed()]
    public function shippingMethod(): ?ShippingData
    {
        if (empty($this->user_data['shipping_method_hash']) || empty($this->location_data['selected_location_code'])) {
            return null;
        }

        $hash = data_get($this->user_data, 'shipping_method_hash');

        return $this->shippingMethodService()->getShippingMethod($hash);
    }

    #[Computed()]
    public function paymentMethods()
    {
        return $this->paymentMethodQueryService()->getPaymentMethods();
    }
};
