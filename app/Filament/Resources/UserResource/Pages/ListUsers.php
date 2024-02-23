<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->label('Todos')
                ->icon('heroicon-o-list-bullet'),
            'admin' => Tab::make()
                ->label('Administradores')
                ->icon('heroicon-o-shield-check')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('panel', '=', 'admin')),
            'app' => Tab::make()
                ->label('Usuários')
                ->icon('heroicon-o-users')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('panel', '=', 'app')),
        ];
    }
}
