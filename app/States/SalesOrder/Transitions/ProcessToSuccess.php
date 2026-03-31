<?php

declare(strict_types=1);

namespace App\States\SalesOrder\Transitions;

use App\Models\SalesOrder;
use App\States\SalesOrder\Success;
use Spatie\ModelStates\Transition;

class ProcessToSuccess extends Transition
{
    public function __construct(
        private SalesOrder $sales_order
    ) {}

    public function handle(): SalesOrder
    {
        $this->sales_order->update([
            'status' => Success::class,
        ]);

        return $this->sales_order;
    }
}
