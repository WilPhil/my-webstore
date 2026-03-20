<?php

use App\Contract\CartServiceInterface;
use App\Data\RegionData;
use App\Services\LocationQueryService;
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
            'user_data.keyword_shipping_location' => 'required',
        ];
    }

    public function updated()
    {
        // $this->location_data['keyword'] = $this->user_data['keyword_shipping_location'];
        data_set($this->location_data, 'keyword', $this->user_data['keyword_shipping_location']);
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

    #[Computed()]
    public function locations(): DataCollection
    {
        $data = [];
        $keyword = data_get($this->location_data, 'keyword');

        if ($keyword == null) {
            $data = [];

            return RegionData::collect($data, DataCollection::class);
        }

        $data = app(LocationQueryService::class)->searchLocationByKeyword($keyword);

        return RegionData::collect($data, DataCollection::class);
    }

    #[Computed()]
    public function location(): ?RegionData
    {
        if (! $this->location_data['selected_location']) {
            return null;
        }

        $code = data_get($this->location_data, 'selected_location');
        $location = app(LocationQueryService::class)->searchLocationByCode($code);

        return $location;
    }
};
