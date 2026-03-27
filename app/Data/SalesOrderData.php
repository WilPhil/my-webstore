<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SalesOrderData extends Data
{
    #[Computed]
    public string $sub_total_formatted;

    #[Computed]
    public string $shipping_cost_formatted;

    #[Computed]
    public string $grand_total_formatted;

    #[Computed]
    public string $due_date_at_formatted;

    #[Computed]
    public string $created_at_formatted;

    public function __construct(
        public string $trx_id,
        public string $status,
        public CustomerData $customer,
        public string $address_line,

        public RegionData $origin,
        public RegionData $destination,

        #[DataCollectionOf(SalesOrderItemData::class)]
        public DataCollection $items,

        public SalesShippingData $shipping,
        public SalesPaymentData $payment,

        public float $sub_total,
        public float $shipping_cost,
        public float $grand_total,

        public Carbon $due_date_at,
        public Carbon $created_at,
    ) {
        $this->sub_total_formatted = Number::currency($sub_total);
        $this->shipping_cost_formatted = Number::currency($shipping_cost);
        $this->grand_total_formatted = Number::currency($grand_total);

        $this->due_date_at_formatted = $due_date_at->translatedFormat('d F Y, H:i');
        $this->created_at_formatted = $created_at->translatedFormat('d F Y, H:i');
    }
}
