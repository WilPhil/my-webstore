<?php

declare(strict_types=1);

namespace App\States\SalesOrder\Transitions;

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
        ]);

        return $this->sales_order;
    }
}
