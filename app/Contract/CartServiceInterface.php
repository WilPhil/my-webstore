<?php

declare(strict_types=1);

namespace App\Contract;

use App\Data\CartData;
use App\Data\CartItemData;

interface CartServiceInterface
{
    public function addOrUpdateItem(CartItemData $item): void;

    public function removeItem(string $sku): void;

    public function getItemBySku(string $sku): ?CartItemData;

    public function getAllItem(): CartData;
}
