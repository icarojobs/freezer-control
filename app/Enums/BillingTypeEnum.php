<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BillingTypeEnum: string implements HasLabel
{
    case BOLETO = 'BOLETO';
    case CREDIT_CARD = 'CREDIT_CARD';
    case UNDEFINED = 'UNDEFINED';
    case DEBIT_CARD = 'DEBIT_CARD';
    case TRANSFER = 'TRANSFER';
    case DEPOSIT = 'DEPOSIT';
    case PIX = 'PIX';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::BOLETO => 'Boleto',
            self::CREDIT_CARD => 'Cartão de Crédito',
            self::UNDEFINED => 'Indefinido',
            self::DEBIT_CARD => 'Cartão de Débito',
            self::TRANSFER => 'Transferência',
            self::DEPOSIT => 'Deposito',
            self::PIX => 'PIX',
        };
    }
}
