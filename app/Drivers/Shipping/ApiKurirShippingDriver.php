<?php

declare(strict_types=1);

namespace App\Drivers\Shipping;

use App\Contract\ShippingDriverInterface;
use App\Data\CartData;
use App\Data\RegionData;
use App\Data\ShippingData;
use App\Data\ShippingServiceData;
use Illuminate\Support\Facades\Http;
use Spatie\LaravelData\DataCollection;

class ApiKurirShippingDriver implements ShippingDriverInterface
{
    public readonly string $driver;

    public function __construct()
    {
        $this->driver = 'apikurir';
    }

    /** @return DataCollection<ShippingServiceData> */
    public function getServices(): DataCollection
    {
        return ShippingServiceData::collect([
            [
                'driver' => $this->driver,
                'code' => 'CTC23',
                'courier' => 'JNE',
                'service' => 'Regular',
            ],
            [
                'driver' => $this->driver,
                'code' => 'CTCSPS23',
                'courier' => 'JNE',
                'service' => 'Express',
            ],
            [
                'driver' => $this->driver,
                'code' => 'NSTD',
                'courier' => 'Ninja Xpress',
                'service' => 'Regular',
            ],
            [
                'driver' => $this->driver,
                'code' => 'NND',
                'courier' => 'Ninja Xpress',
                'service' => 'Express',
            ],
        ], DataCollection::class);
    }

    public function getRate(
        RegionData $origin,
        RegionData $destination,
        CartData $cart,
        ShippingServiceData $shipping_service
    ): ?ShippingData {
        $response = Http::withBasicAuth(
            config('shipping.api_kurir.username'),
            config('shipping.api_kurir.password')
        )
            ->post('https://sandbox.apikurir.id/shipments/v1/open-api/rates', [
                'isUseInsurance' => true,
                'isPickup' => true,
                'isCod' => false,
                'dimensions' => [10, 10, 10],
                'weight' => $cart->totalWeight,
                'packagePrice' => $cart->totalPrice,
                'origin' => [
                    'postalCode' => $origin->postal_code,
                ],
                'destination' => [
                    'postalCode' => $destination->postal_code,
                ],
                'logistics' => [$shipping_service->courier],
                'services' => [$shipping_service->service],
            ]);
        $data = $response->collect('data')->flatten(1)->values()->first();

        if (empty($data)) {
            return null;
        }

        $estimated_delivery = data_get($data, 'minDuration').' - '.data_get($data, 'maxDuration').' '.data_get($data, 'durationType');

        return ShippingData::from([
            'driver' => $this->driver,
            'courier' => data_get($data, 'logisticName'),
            'service' => data_get($data, 'serviceType'),
            'estimated_delivery' => $estimated_delivery,
            'cost' => data_get($data, 'price'),
            'weight' => data_get($data, 'weight'),
            'origin' => $origin,
            'destination' => $destination,
            'logo_url' => data_get($data, 'logoUrl'),
        ]);
    }
}
