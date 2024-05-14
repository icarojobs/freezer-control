<?php

namespace App\Filament\App\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Product;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class createPanelUser extends CreateRecord
{
    protected static string $resource = Product::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome site principal x'),
            ])->statePath('data')->model(Product::class)->columns([
                'default' => 2,
                'sm' => 1,
                'md' => 2,
                'lg' => 2,
                'xl' => 2,
                '2xl' => 2
            ]);
    }

}
