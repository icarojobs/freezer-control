<?php

namespace App\Filament\App\Pages;

use App\Models\Product;
use Filament\Pages\Page;
use Filament\Tables\Table;

class FreezerApp extends Page
{


    protected static ?string $model = Product::class;

    protected static ?string $activeNavigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = "Meu freezer";

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.freezer-app';

    /**
     * @param Product $product;
     */
    public Product $product;

    public ?array $data = [];

    public function mount(): void
    {

    }


    public static function get(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->badge()
                    ->searchable(),

            ]);
    }
}
