<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentTypeEnum: string implements HasLabel, HasColor
{
    case PIX = 'PIX';
    case CREDIT_CARD = 'CREDIT_CARD';

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
}
