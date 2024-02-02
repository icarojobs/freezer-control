<?php

declare(strict_types=1);

namespace App\Enums;

enum PanelTypeEnum: string
{
    case ADMIN = 'admin';
    case APP = 'app';
}
