<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\SalesOrderData;
use App\Data\SalesOrderItemData;
use App\Events\ShippingReceiptNumberUpdatedEvent;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;

class SalesOrderService
{
    public function updateShippingReceiptNumber(SalesOrderData $sales_order, string $number): SalesOrderData
    {
        $query = SalesOrder::query()->whereTrxId($sales_order->trx_id)->first();
        $query->update([
            'shipping_receipt_number' => $number,
        ]);

        $sales_order_data = SalesOrderData::fromModel($query->refresh());

        event(
            new ShippingReceiptNumberUpdatedEvent($sales_order_data)
        );

        return $sales_order_data;
    }

    public function updatePaymentPayload(SalesOrderData $sales_order, array $payload): SalesOrderData
    {
        SalesOrder::whereTrxId($sales_order->trx_id)
            ->update([
                'payment_payload' => array_merge($sales_order->payment->payload, $payload),
            ]);

        return SalesOrderData::fromModel(
            SalesOrder::whereTrxId($sales_order->trx_id)->first()
        );
    }

    public function updateDueStock(SalesOrderData $sales_order): void
    {
        $sales_order->items
            ->toCollection()
            ->each(function (SalesOrderItemData $item) {
                DB::transaction(function () use ($item) {
                    Product::lockForUpdate()
                        ->update([
                            'stock' => $item->quantity,
                        ]);
                });
            });
    }
}
