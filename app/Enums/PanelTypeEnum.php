<?php

declare(strict_types=1);

namespace App\Enums;

enum PanelTypeEnum: string
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
}
