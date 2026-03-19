<?php

use App\Contract\CartServiceInterface;
use App\Data\RegionData;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
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
        $this->location_data['keyword'] = $this->user_data['keyword_shipping_location'];
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
        $allData = [
            [
                'code' => '001',
                'province' => 'Jawa Barat',
                'regency' => 'Bandung',
                'district' => 'Cikunir',
                'village' => 'Bondo',
                'postal_code' => '1234',
            ],
            [
                'code' => '002',
                'province' => 'Jawa Tengah',
                'regency' => 'Magelang',
                'district' => 'Magelang',
                'village' => 'Muntilan',
                'postal_code' => '4321',
            ],
        ];
        $keyword = data_get($this->location_data, 'keyword');

        if (Str::length($keyword) <= 3) {
            return RegionData::collect([], DataCollection::class);
        }

        $data = collect($allData)->filter(function ($item) use ($keyword) {
            $item_name = implode(' ', $item);

            return Str::contains($item_name, $keyword, true);
        });

        return RegionData::collect($data, DataCollection::class);
    }

    #[Computed()]
    public function location(): ?RegionData
    {
        if (! $this->location_data['selected_location']) {
            return null;
        }

        return $this->locations->first(fn ($location) => $location->code == $this->location_data['selected_location']);
    }
};
