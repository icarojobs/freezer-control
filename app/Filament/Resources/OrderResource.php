<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PaymentTypeEnum;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Get;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components\Group;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use App\Filament\Forms\Components\PtbrMoney;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Contracts\Database\Eloquent\Builder;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $activeNavigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $pluralModelLabel = "Vendas";
    protected static ?string $modelLabel = "Venda";

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderBy('created_at', 'desc');
            })
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Nome do cliente')
                    ->description(function (Order $record) {
                        return $record->customer->document;
                    })
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data da compra')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([])
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

    public static function getCustomerFormDetails(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    Section::make()
                        ->schema([
                            Select::make('customer_id')
                                ->label('Cliente')
                                ->required()
                                ->relationship('customer', 'name')
                                ->live(debounce: 250)
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state != null) {
                                        $customer = Customer::find($state);
                                        $set('customer_name', $customer->name);
                                        $set('customer_email', $customer->email);
                                        $set('customer_birthdate', $customer->birthdate->format('d/m/Y'));
                                    }

                                    if ($customer->birthdate->age < 18) {
                                        Notification::make()
                                            ->danger()
                                            ->title('Pedidos só podem ser realizados para clientes maiores de 18 anos.')
                                            ->duration(8000)
                                            ->send();

                                        $set('customer_id', null);
                                    }
                                })
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label('Nome completo')
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('email')
                                        ->label('Email')
                                        ->required()
                                        ->email()
                                        ->maxLength(255)
                                        ->unique(),

                                    TextInput::make('document')
                                        ->label('Documento')
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('mobile')
                                        ->label('Celular')
                                        ->extraAlpineAttributes(['x-mask' => '(99) 99999-9999'])
                                        ->maxLength(255),

                                    DatePicker::make('birthdate')
                                        ->label('Data Nascimento')
                                        ->displayFormat('d/m/Y')
                                        ->required(),


                                ])
                                ->createOptionAction(function (Action $action) {
                                    return $action
                                        ->modalHeading('Novo cliente')
                                        ->modalSubmitActionLabel('Cadastrar cliente')
                                        ->modalWidth('lg')
                                        ->closeModalByClickingAway(false);
                                }),

                            TextInput::make('customer_email')
                                ->label('Email')
                                ->hidden(fn ($get) => $get('customer_id') == null)
                                ->disabled(),

                            TextInput::make('customer_birthdate')
                                ->label('Data Nascimento')
                                ->hidden(fn ($get) => $get('customer_id') == null)
                                ->disabled(),
                        ])
                        ->columnSpan(2),
                    Section::make()
                        ->schema([
                            Placeholder::make('total')
                                ->label('Últimas Compras')
                                ->content(function ($get) {
                                    if (is_null($get('customer_id'))) {
                                        return "Nenhum cliente selecionado";
                                    }

                                    return new HtmlString(
                                        view(
                                            view: 'orders.latest-orders',
                                            data: static::getSelectedCustomer(
                                                $get('customer_id'),
                                            )
                                        )->render()
                                    );
                                }),
                        ])
                        ->columnSpan(1)
                ]),

        ];
    }

    public static function getPaymentFormDetails(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    Section::make()
                        ->schema([
                            Select::make('billingType')
                                ->label('Forma de Pagamento')
                                ->options(PaymentTypeEnum::class)
                                ->preload()
                                ->searchable(),

//                            Placeholder::make('Formulário de pagamento')
//                                ->content(view('checkout.payment')),
                        ])
                        ->columnSpan(2),
                    Section::make()
                        ->schema([
                            Placeholder::make('Resumo da compra')
                                ->content('Under construction'),
                        ])
                        ->columnSpan(1)
                ])
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        $product = Product::all();

        return Repeater::make('items')
            ->schema([
                Select::make('product_id')
                    ->label('Item')
                    ->options($product->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        $price = Product::find($state)?->sale_price;
                        $set('unit_price', $price ?? 0);
                        $set('sub_total', $price ?? 0);
                        $set('quantity', 1);
                    })
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan(3),



                TextInput::make('quantity')
                    ->label('Quantidade')
                    ->numeric()
                    ->reactive()
                    ->minValue(1)
                    ->hint(function ($get) {
                        $stock = Product::find($get('product_id'))?->in_stock;

                        if (!is_null($stock)) return "{$stock} em estoque";

                        return null;
                    })
                    ->afterStateUpdated(function ($state, $set, $get) {

                        $unit_price = $get('unit_price');

                        $sub_total = number_format((float)$state * $unit_price, 2, '.', '');

                        $set('sub_total', $sub_total);
                    })
                    ->columnSpan(2),

                PtbrMoney::make('unit_price')
                    ->label('Valor Unitário')
                    ->default(0.0)
                    ->disabled()
                    ->columnSpan(1),

                PtbrMoney::make('sub_total')
                    ->label('Subtotal')
                    ->default(0.0)
                    ->disabled()
                    ->columnSpan(1),
            ])
            ->addActionLabel('Adicionar item')
            ->maxItems($product->count())
            ->columns(7)
            ->columnSpanFull();
    }



    public static function getSelectedCustomer(string|int $customerId): array
    {

        $orders = Order::where('customer_id', $customerId)->get();

        return compact('orders');
    }
}
