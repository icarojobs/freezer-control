<?php

declare(strict_types=1);

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum PanelTypeEnum: string implements HasLabel, HasColor, HasIcon
{
    case ADMIN  =   "admin";
    case APP    =   "app";



    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::APP => 'Usuário',
        };
    }


    public function getColor(): string | array | null
    {
        return match ($this) {
            self::ADMIN => 'success',
            self::APP => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match($this)
        {
            self::ADMIN => 'heroicon-o-shield-check',
            self::APP   => 'heroicon-o-users',
        };
    }
}
