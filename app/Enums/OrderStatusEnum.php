<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'PENDING';
    case PAID = 'PAID';
    case FAILED = 'FAILED';
}
