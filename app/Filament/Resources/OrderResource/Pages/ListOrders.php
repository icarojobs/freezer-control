<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successRedirectUrl(OrderResource::getUrl('index')),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All')->label('Todas'),
            'processing' => Tab::make()->label('Pendentes')->query(fn ($query) => $query->where('status', 'PENDING')),
            'paid' => Tab::make()->label('Pagas')->query(fn ($query) => $query->where('status', 'PAID')),
            'failed' => Tab::make()->label('Falharam')->query(fn ($query) => $query->where('status', 'FAILED')),
        ];
    }
}
