<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;

enum PaymentTypeEnum: string implements HasLabel, HasColor, HasIcon
{
    case CREDIT_CARD = 'credit_card';
    case PIX = 'pix';


    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PIX => 'success',
            self::CREDIT_CARD => 'warning',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PIX => 'Pix',
            self::CREDIT_CARD => 'Cartão de Crédito',
        };
    }

    public function getIcon(): ?string
    {
        return match($this)
        {
            self::PIX => 'fab-pix',
            self::CREDIT_CARD => 'heroicon-s-credit-card',
        };
    }
}
