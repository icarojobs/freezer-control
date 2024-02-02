<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $modelLabel = 'Produto';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome do Produto')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('cost_price')
                    ->label('Preço de Custo')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('sale_price')
                    ->label('Preço de Venda')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('in_stock')
                    ->label('Estoque Atual')
                    ->required()
                    ->numeric()
                    ->disabled()
                    ->default(0),

                Forms\Components\FileUpload::make('image')
                    ->label('Foto do Produto')
                    ->image()
                    ->openable()
                    ->downloadable()
                    ->directory('images'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(''),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nome do Produto')
                    ->searchable(),

                Tables\Columns\TextColumn::make('cost_price')
                    ->label('Preço de Custo')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sale_price')
                    ->label('Preço de Venda')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('in_stock')
                    ->label('Estoque Atual')
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
