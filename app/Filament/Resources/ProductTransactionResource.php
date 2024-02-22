<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\ProductTransactionTypeEnum;
use App\Filament\Resources\ProductTransactionResource\Pages;
use App\Models\ProductTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductTransactionResource extends Resource
{
    protected static ?string $model = ProductTransaction::class;

    protected static ?string $navigationGroup = "Logística";
    protected static ?string $activeNavigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $pluralModelLabel = "Movimentações";
    protected static ?string $modelLabel = "Movimentação";

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Produto')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('description')
                    ->label('Descrição')
                    ->disabled()
                    ->maxLength(255),

                Forms\Components\Select::make('type')
                    ->label('Tipo de Movimentação')
                    ->options(ProductTransactionTypeEnum::class)
                    ->native(false),

                Forms\Components\TextInput::make('quantity')
                    ->label('Quantidade')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produto')
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo de Movimentação')
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantidade')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductTransactions::route('/'),
            'create' => Pages\CreateProductTransaction::route('/create'),
            'edit' => Pages\EditProductTransaction::route('/{record}/edit'),
        ];
    }
}
