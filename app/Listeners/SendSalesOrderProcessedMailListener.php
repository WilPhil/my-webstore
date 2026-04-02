<?php

namespace App\Listeners;

use App\Events\SalesOrderProcessedEvent;
use App\Mail\SalesOrderProcessedMail;
use Illuminate\Support\Facades\Mail;

class SendSalesOrderProcessedMailListener
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
    public function handle(SalesOrderProcessedEvent $event): void
    {
        Mail::queue(
            new SalesOrderProcessedMail($event->sales_order)
        );
    }
}
