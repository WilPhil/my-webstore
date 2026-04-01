<?php

namespace App\Listeners;

use App\Events\SalesOrderCreated;
use App\Services\PaymentMethodQueryService;

class GeneratePaymentLink
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SalesOrderCreated $event): void
    {
        app(PaymentMethodQueryService::class)
            ->getDriver($event->sales_order->payment)
            ->process($event->sales_order);
    }
}
