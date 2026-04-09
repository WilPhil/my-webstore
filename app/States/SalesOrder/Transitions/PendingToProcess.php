<?php

declare(strict_types=1);

namespace App\States\SalesOrder\Transitions;

use App\Data\SalesOrderData;
use App\Events\SalesOrderProcessedEvent;
use App\Models\SalesOrder;
use App\States\SalesOrder\Process;
use Spatie\ModelStates\Transition;

class PendingToProcess extends Transition
{
    public function __construct(
        private SalesOrder $sales_order
    ) {}

    public function handle(): SalesOrder
    {
        $this->sales_order->update([
            'status' => Process::class,
            'payment_paid_at' => now('Asia/Jakarta'),
        ]);

        SalesOrderProcessedEvent::dispatch(
            SalesOrderData::fromModel($this->sales_order)
        );

        return $this->sales_order;
    }
}
