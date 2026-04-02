<?php

namespace App\Listeners;

use App\Events\SalesOrderSuccessedEvent;
use App\Mail\SalesOrderSuccessedMail;
use Illuminate\Support\Facades\Mail;

class SendSalesOrderSuccessedMailListener
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
    public function handle(SalesOrderSuccessedEvent $event): void
    {
        Mail::queue(
            new SalesOrderSuccessedMail($event->sales_order)
        );
    }
}
