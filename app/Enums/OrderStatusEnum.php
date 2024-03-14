<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum OrderStatusEnum: string implements HasLabel, HasColor, HasIcon
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

    public function getIcon(): ?string
    {
        return match($this)
        {
            self::PENDING => 'heroicon-o-bell-alert',
            self::PAID => 'heroicon-o-check-badge',
            self::FAILED => 'heroicon-o-x-circle',
        };
    }
}
