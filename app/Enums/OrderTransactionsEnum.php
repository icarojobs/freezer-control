<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderTransactionsEnum: string implements HasLabel
{
    case PENDING = 'PENDING';
    case RECE = 'RECE';
    case CONFIRMED = 'CONFIRMED'; 
    case OVERDUE = 'OVERDUE'; 
    case REFUNDED = 'REFUNDED';
    case RECEIVED_IN_CASH = 'RECEIVED_IN_CASH'; 
    case REFUND_REQUESTED = 'REFUND_REQUESTED'; 
    case REFUND_IN_PROGRESS = 'REFUND_IN_PROGRESS'; 
    case CHARGEBACK_REQUESTED = 'CHARGEBACK_REQUESTED'; 
    case CHARGEBACK_DISPUTE = 'CHARGEBACK_DISPUTE'; 
    case AWAITING_CHARGEBACK_REVERSAL = 'AWAITING_CHARGEBACK_REVERSAL'; 
    case DUNNING_REQUESTED = 'DUNNING_REQUESTED'; 
    case DUNNING_RECEIVED = 'DUNNING_RECEIVED'; 
    case AWAITING_RISK_ANALYSIS = 'AWAITING_RISK_ANALYSIS';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::RECE => 'Receber',
            self::CONFIRMED => 'Confirmado',
            self::OVERDUE => 'Atrasada',
            self::REFUNDED => 'Devolveu',
            self::RECEIVED_IN_CASH => 'Recebido em Dinheiro',
            self::REFUND_REQUESTED => 'Reembolocamento Solicitado',
            self::REFUND_IN_PROGRESS => 'Reembolso em Andamento',
            self::CHARGEBACK_REQUESTED => 'Retorno Requeridos',
            self::CHARGEBACK_DISPUTE => 'Estorno Disputa',
            self::AWAITING_CHARGEBACK_REVERSAL => 'Aguardando Reversão de Retorno',
            self::DUNNING_REQUESTED => 'Cobrança Solicitada',
            self::DUNNING_RECEIVED => 'Cobrança Recebida',
            self::AWAITING_RISK_ANALYSIS => 'Aguardando Análise de Risco',
        };
    }
}
