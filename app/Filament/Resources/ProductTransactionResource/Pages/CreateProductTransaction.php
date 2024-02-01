<?php

namespace App\Filament\Resources\ProductTransactionResource\Pages;

use App\Filament\Resources\ProductTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductTransaction extends CreateRecord
{
    protected static string $resource = ProductTransactionResource::class;
}
