<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductTransactionTypeEnum: string implements HasLabel, HasColor
{
    case BUY = 'buy';
    case SALE = 'sale';
    case INVENTORY = 'inventory';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BUY => 'Compra',
            self::SALE => 'Venda',
            self::INVENTORY => 'Inventário',
        };
    }


    public function getColor(): string | array | null
    {
        return match ($this) {
            self::BUY => 'danger',
            self::SALE => 'success',
            self::INVENTORY => 'warning',
        };
    }
}
