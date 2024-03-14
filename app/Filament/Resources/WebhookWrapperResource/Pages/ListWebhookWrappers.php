<?php

namespace App\Filament\Resources\WebhookWrapperResource\Pages;

use App\Filament\Resources\WebhookWrapperResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebhookWrappers extends ListRecords
{
    protected static string $resource = WebhookWrapperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
