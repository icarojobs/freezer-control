<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $navigationLabel = 'Visualizar usuário';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
