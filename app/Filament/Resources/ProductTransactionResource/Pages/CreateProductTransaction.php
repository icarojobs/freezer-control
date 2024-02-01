<?php

namespace App\Filament\Resources\ProductTransactionResource\Pages;

use App\Enums\ProductTransactionTypeEnum;
use App\Filament\Resources\ProductTransactionResource;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProductTransaction extends CreateRecord
{
    protected static string $resource = ProductTransactionResource::class;

    protected function afterCreate(): void
    {
        if ($this->data['type'] === ProductTransactionTypeEnum::BUY->value) {
            Product::query()
                ->where('id', $this->data['product_id'])
                ->increment('in_stock', (int)$this->data['quantity']);

            $this->updateDescription();

            return;
        }

        if ($this->data['type'] === ProductTransactionTypeEnum::SALE->value) {
            Product::query()
                ->where('id', $this->data['product_id'])
                ->decrement('in_stock', (int)$this->data['quantity']);

            $this->updateDescription();

            return;
        }

        $this->updateStock();
        $this->updateDescription();
    }

    private function updateStock(): void
    {
        Product::query()
            ->where('id', $this->data['product_id'])
            ->update(['in_stock' => (int)$this->data['quantity']]);
    }

    private function updateDescription(): void
    {
        $message = "O usuário ". Auth::user()->name ." realizou a movimentação de {$this->getType()} de {$this->data['quantity']} unidades.";

        $this->getRecord()->update(['description' => $message]);
    }

    private function getType(): string
    {
        if ($this->data['type'] === 'buy') {
            return 'compra';
        }

        if ($this->data['type'] === 'sale') {
            return 'venda';
        }

        return 'inventário';
    }
}
