<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum OrderStatusEnum: string implements HasLabel, HasColor
{
    case PENDING = 'PENDING';
    case PAID = 'PAID';
    case FAILED = 'FAILED';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::PAID => 'Paga',
            self::FAILED => 'Falhou',
        };
    }


    public function getColor(): string | array | null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PAID => 'success',
            self::FAILED => 'danger',
        };
    }
}
