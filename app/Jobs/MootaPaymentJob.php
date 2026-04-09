<?php

namespace App\Jobs;

use App\Services\SalesOrderService;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class MootaPaymentJob extends ProcessWebhookJob
{
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = $this->webhookCall;

        collect(data_get($data, 'payload'))->each(function ($item) {
            if (! data_get($item, 'payment_detail.order_id')) {
                return;
            }

            $total_without_code = data_get($item, 'payment_detail.total') - data_get($item, 'payment_detail.unique_code');

            app(SalesOrderService::class)->updateSalesOrderStatus(
                data_get($item, 'payment_detail.order_id'),
                $total_without_code
            );
        });
    }
}
