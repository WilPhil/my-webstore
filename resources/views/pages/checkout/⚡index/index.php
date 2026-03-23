<?php

use App\Contract\CartServiceInterface;
use App\Data\RegionData;
use App\Data\ShippingData;
use App\Services\LocationQueryService;
use App\Services\ShippingMethodService;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\LaravelData\DataCollection;

new class extends Component
{
    public array $user_data = [
        'full_name' => null,
        'email_address' => null,
        'phone_number' => null,
        'shipping_address' => null,
        'keyword_shipping_location' => null,
    ];

    public array $location_data = [
        'keyword' => null,
        'selected_location' => null,
    ];

    public array $shipping_data = [
        'shipping_method' => null,
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
    public function cartService()
    {
        return app(CartServiceInterface::class);
    }

    #[Computed()]
    public function locationQueryService()
    {
        return app(LocationQueryService::class);
    }

    public function mount()
    {
        $shipping_total = 0;
        $grand_total = $this->cartService()->getAllItem()->totalPrice + $shipping_total;

        $this->cart_summaries = [
            'weight_total' => $this->cartService()->getAllItem()->totalWeight,
            'sub_total' => $this->cartService->getAllItem()->totalPrice,
            'sub_total_formatted' => $this->cartService->getAllItem()->totalPriceFormatted,
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
            'user_data.keyword_shipping_location' => 'required',
        ];
    }

    public function updatedUserDataKeywordShippingLocation($value)
    {
        data_set($this->location_data, 'keyword', $value);
    }

    public function placeAnOrder()
    {
        $this->validate();

        dd($this->user_data);
    }

    #[Computed()]
    public function items()
    {
        return $this->cartService()->getAllItem()->items;
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
        if (! $this->location_data['selected_location']) {
            return null;
        }

        $code = data_get($this->location_data, 'selected_location');
        $location = $this->locationQueryService()->searchLocationByCode($code);

        // $this->dispatch('shipping-method');

        return $location;
    }

    /** @return DataCollection<ShippingData> */
    #[Computed()]
    public function shippingMethods(): DataCollection|Collection
    {
        if (data_get($this->location_data, 'selected_location') == null) {
            return new DataCollection(ShippingData::class, []);
        }

        $location_service = $this->locationQueryService();
        $shipping_service = app(ShippingMethodService::class);
        $shipping_origin_code = config('shipping.shipping_origin_code');
        $cart_service = $this->cartService();

        return $shipping_service->getShippingMethods(
            $location_service->searchLocationByCode($shipping_origin_code),
            $location_service->searchLocationByCode(data_get($this->location_data, 'selected_location')),
            $cart_service->getAllItem()
        )
            ->toCollection()
            ->groupBy('service');
    }
};
