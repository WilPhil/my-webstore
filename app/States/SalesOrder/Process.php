<?php

declare(strict_types=1);

namespace App\States\SalesOrder;

class Process extends SalesOrderState
{
    public function label(): string
    {
        return 'Memproses pembayaran.';
    }
}
