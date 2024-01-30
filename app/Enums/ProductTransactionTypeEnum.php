<?php

namespace App\Enums;

enum ProductTransactionTypeEnum: string
{
    case BUY = 'buy';
    case SALE = 'sale';
    case INVENTORY = 'inventory';
}
