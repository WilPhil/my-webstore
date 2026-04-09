<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\SalesOrderData;
use App\Data\SalesOrderItemData;
use App\Events\ShippingReceiptNumberUpdatedEvent;
use App\Models\Product;
use App\Models\SalesOrder;
use App\States\SalesOrder\Pending;
use App\States\SalesOrder\Process;
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

    public function updateSalesOrderStatus(
        string $trx_id,
        float $total
    ): void {
        $sales_order = SalesOrder::query()
            ->whereTrxId($trx_id)
            ->whereGrandTotal($total)
            ->whereStatus(Pending::class)
            ->first();

        $sales_order->status->transitionTo(Process::class);
    }
}
