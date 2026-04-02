<?php

namespace App\Listeners;

use App\Events\ShippingReceiptNumberUpdatedEvent;
use App\Mail\ShippingReceiptNumberUpdatedMail;
use Illuminate\Support\Facades\Mail;

class SendShippingReceiptNumberUpdatedMailListener
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
    public function handle(ShippingReceiptNumberUpdatedEvent $event): void
    {
        Mail::queue(
            new ShippingReceiptNumberUpdatedMail($event->sales_order)
        );
    }
}
