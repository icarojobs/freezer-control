<?php

namespace App\Enums;

enum ProductTransactionTypeEnum: string
{
    case BUY = 'buy';
    case SALE = 'sale';
    case INVENTORY = 'inventory';

    public function getLabels(): string
    {
        return match ($this) {
            self::BUY => 'buy',
            self::SALE => 'sale',
            self::INVENTORY => 'inventory',
        };
    }
}
