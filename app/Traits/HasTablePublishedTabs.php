<?php

namespace App\Traits;

use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

trait HasTablePublishedTabs {

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
            ->label('Todos')
            ->icon('heroicon-o-bars-4'),
            'admin' => Tab::make()
                ->label('Administradores')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('panel', '=', 'admin')),
            'app' => Tab::make()
                ->label('Usuários')
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('panel', '=', 'app')),
        ];
    }
}

