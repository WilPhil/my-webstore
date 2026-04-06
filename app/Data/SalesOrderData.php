<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\SalesOrder;
use App\States\SalesOrder\SalesOrderState;
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
        public SalesOrderState $status,
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

    public static function fromModel(SalesOrder $sales_order): self
    {
        return new self(
            trx_id: $sales_order->trx_id,
            status: $sales_order->status,
            customer: new CustomerData(
                full_name: $sales_order->customer_full_name,
                email_address: $sales_order->customer_email_address,
                phone_number: $sales_order->customer_phone_number,
            ),
            address_line: $sales_order->address_line,
            origin: new RegionData(
                code: $sales_order->origin_code,
                province: $sales_order->origin_province,
                regency: $sales_order->origin_regency,
                district: $sales_order->origin_district,
                village: $sales_order->origin_village,
                postal_code: $sales_order->origin_postal_code,
            ),
            destination: new RegionData(
                code: $sales_order->destination_code,
                province: $sales_order->destination_province,
                regency: $sales_order->destination_regency,
                district: $sales_order->destination_district,
                village: $sales_order->destination_village,
                postal_code: $sales_order->destination_postal_code,
            ),
            items: SalesOrderItemData::collect($sales_order->items->toArray(), DataCollection::class),
            shipping: new SalesShippingData(
                driver: $sales_order->shipping_driver,
                receipt_number: $sales_order->shipping_receipt_number,
                courier: $sales_order->shipping_courier,
                service: $sales_order->shipping_service,
                estimated_delivery: $sales_order->shipping_estimated_delivery,
                cost: $sales_order->shipping_cost,
                weight: $sales_order->shipping_weight,
            ),
            payment: new SalesPaymentData(
                driver: $sales_order->payment_driver,
                method: $sales_order->payment_method,
                label: $sales_order->payment_label,
                payload: $sales_order->payment_payload,
                paid_at: Carbon::parse($sales_order->payment_paid_at),
            ),
            sub_total: $sales_order->sub_total,
            shipping_cost: $sales_order->shipping_total,
            grand_total: $sales_order->grand_total,
            due_date_at: Carbon::parse($sales_order->due_date_at),
            created_at: $sales_order->created_at,
        );
    }
}
