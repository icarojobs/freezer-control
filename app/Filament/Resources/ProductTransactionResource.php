<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\ProductTransactionTypeEnum;
use App\Filament\Resources\ProductTransactionResource\Pages;
use App\Models\ProductTransaction;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductTransactionResource extends Resource
{
    protected static ?string $model = ProductTransaction::class;

    protected static ?string $navigationGroup = 'Logística';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $activeNavigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $slug = 'movimentacoes';

    protected static ?string $modelLabel = 'Movimentação';

    protected static ?string $pluralModelLabel = 'Movimentações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Produto')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantidade')
                            ->required()
                            ->numeric(),

                        Forms\Components\ToggleButtons::make('type')
                            ->required()
                            ->label('Tipo de Movimentação')
                            ->options(ProductTransactionTypeEnum::class)
                            ->inline()
                            ->reactive(),

                        Forms\Components\Select::make('supplier_id')
                            ->label('Fornecedor')
                            ->relationship('supplier', 'registered_name')
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->hidden(fn ($get) => $get('type') !== 'buy'),

                        Forms\Components\Placeholder::make('description')
                            ->label('Descrição')
                            ->hidden(fn (?ProductTransaction $record): bool => $record == null)
                            ->content(fn ($state) => $state),
                    ])
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
                    ->wrap()
                    ->label('Descrição')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo de Movimentação')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantidade')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier.registered_name')
                    ->label('Fornecedor')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                ])->tooltip('Menu')
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

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }
}
