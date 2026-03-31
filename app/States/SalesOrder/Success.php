<?php

declare(strict_types=1);

namespace App\States\SalesOrder;

class Success extends SalesOrderState
{
    public function label(): string
    {
        return 'Pembayaran sukses.';
    }
}
