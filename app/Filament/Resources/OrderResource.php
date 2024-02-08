<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $modelLabel = 'Venda';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static float $total = 0.0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // todo: não esquecer de adicionar o usuário logado nesse campo....
//                Forms\Components\TextInput::make('user_id')
//                    ->numeric(),

                Wizard::make([
                    Wizard\Step::make('Itens')
                        ->schema([
                            Repeater::make('items')
                                ->schema([
                                    Grid::make(6)
                                        ->schema([
                                            Select::make('product_id')
                                                ->label('Item')
                                                ->options(Product::all()->pluck('name', 'id'))
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->reactive()
                                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                    $price = Product::find($state)?->sale_price;
                                                    $set('unit_price', $price ?? 0);
                                                })
                                                ->distinct()
                                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                                ->columnSpan(3),

                                            TextInput::make('quantity')
                                                ->label('Quantidade')
                                                ->numeric()
                                                ->default(1)
                                                ->columnSpan(2),

                                            TextInput::make('unit_price')
                                                ->label('Valor Unitário')
                                                ->default(0.0)
                                                ->disabled()
                                                ->columnSpan(1),
                                        ]),

                                ])->columnSpanFull(),

                            // Atualizar esse total com base na quantidade de itens no carrinho
                            TextInput::make('total')
                                ->disabled()
                                ->reactive()
                                ->default(self::$total)
                                ->columnSpan(2),
                        ])->columns(6),
                    Wizard\Step::make('Pagamento')
                        ->schema([
                            // todo: criar uma custom (view/component)
                        ]),
                ])->columnSpanFull(),

//                Forms\Components\TextInput::make('status')
//                    ->required()
//                    ->maxLength(255)
//                    ->default('PENDING'),
            ])->columns(8);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
