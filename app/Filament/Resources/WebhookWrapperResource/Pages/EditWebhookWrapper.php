<?php

namespace App\Filament\Resources\WebhookWrapperResource\Pages;

use App\Filament\Resources\WebhookWrapperResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebhookWrapper extends EditRecord
{
    protected static string $resource = WebhookWrapperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
