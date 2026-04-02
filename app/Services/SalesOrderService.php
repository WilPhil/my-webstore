<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\SalesOrderData;
use App\Events\ShippingReceiptNumberUpdatedEvent;
use App\Models\SalesOrder;

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
}
