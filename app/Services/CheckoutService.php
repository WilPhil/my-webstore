<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\CartItemData;
use App\Data\CheckoutData;
use App\Data\SalesOrderData;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Support\Str;

class CheckoutService
{
    public function makeAnOrder(CheckoutData $checkout): SalesOrderData
    {
        DB::transaction(function () use ($checkout) {
            $customer_data = $checkout->customer;
            $region_origin_data = $checkout->origin;
            $region_destination_data = $checkout->destination;
            $shipping_data = $checkout->shipping;
            $payment_data = $checkout->payment;

            $date = Carbon::now()->format('Ymd');
            $rand = strtoupper(Str::random(5));
            $sales_order = SalesOrder::query()->create([
                'trx_id' => "TRX-$date-$rand",
                'status' => 'pending',

                // Customer
                'customer_full_name' => $customer_data->full_name,
                'customer_email_address' => $customer_data->email_address,
                'customer_phone_number' => $customer_data->phone_number,

                'address_line' => $checkout->shipping_address,

                // Region origin
                'origin_code' => $region_origin_data->code,
                'origin_province' => $region_origin_data->province,
                'origin_regency' => $region_origin_data->regency,
                'origin_district' => $region_origin_data->district,
                'origin_village' => $region_origin_data->village,
                'origin_postal_code' => $region_origin_data->postal_code,

                // Region destination
                'destination_code' => $region_destination_data->code,
                'destination_province' => $region_destination_data->province,
                'destination_regency' => $region_destination_data->regency,
                'destination_district' => $region_destination_data->district,
                'destination_village' => $region_destination_data->village,
                'destination_postal_code' => $region_destination_data->postal_code,

                // Shipping
                'shipping_driver' => $shipping_data->driver,
                'shipping_receipt_number' => '',
                'shipping_courier' => $shipping_data->courier,
                'shipping_estimated_delivery' => $shipping_data->estimated_delivery,
                'shipping_cost' => $shipping_data->cost,
                'shipping_weight' => $shipping_data->weight,

                // Payment
                'payment_driver' => $payment_data->driver,
                'payment_method' => $payment_data->method,
                'payment_label' => $payment_data->label,
                'payment_payload' => $payment_data->payload,
                'payment_paid_at' => null,

                'sub_total' => $checkout->sub_total,
                'shipping_total' => $checkout->shipping_cost,
                'grand_total' => $checkout->grand_total,

                'due_date_at' => Carbon::now()->addDays(1),
            ]);
            $sales_order_items = collect([]);

            /** @var CartItemData $item */
            foreach ($checkout->cart->items as $item) {
                $product = Product::where('sku', $item->sku)->lockForUpdate()->firstOrFail();

                if ($product->stock < $item->quantity) {
                    throw new ItemNotFoundException('Stock is not available.');
                }

                $sales_order_items->push([
                    'name' => $item->product()->name,
                    'slug' => $item->product()->slug,
                    'sku' => $item->sku,
                    'tags' => $item->product()->tags,
                    'description' => $item->product()->description ?? '',
                    'cover_url' => $item->product()->coverUrl ?? '',
                    'stock' => $item->quantity,
                    'weight' => $item->weight,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                ]);

                $product->stock -= $item->quantity;
                $product->save();
            }

            $sales_order->items()->createMany($sales_order_items);
        });
    }
}
