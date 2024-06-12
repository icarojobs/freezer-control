<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Get;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\PaymentTypeEnum;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components\Group;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use App\Filament\Forms\Components\PtbrMoney;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\OrderResource\Pages;
use Filament\Forms\Components\Hidden;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $activeNavigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $modelLabel = 'Venda';

    protected static ?string $pluralModelLabel = 'Vendas';

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
                Tables\Columns\TextColumn::make('transaction.charge_id')
                    ->label('ASAAS ID'),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Nome do cliente')
                    ->description(function (Order $record) {
                        return $record->customer->document;
                    })
                    ->numeric(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->money('BRL'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data da compra')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([])
            ->bulkActions([]);
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
                                        $set('customer_external_id', $customer->customer_id);
                                        $set('customer_document', $customer->document);
                                        $set('customer_mobile', $customer->mobile);
                                        $set('customer_birthdate', $customer->birthdate->format('d/m/Y'));
                                    }

                                    if ($customer->birthdate->age < 18) {
                                        Notification::make()
                                            ->danger()
                                            ->title('Pedidos só podem ser realizados para clientes maiores de 18 anos.')
                                            ->duration(8000)
                                            ->send();

                                        $set('id', null);
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
                                    Hidden::make('customer_external_id'),


                                ])
                                ->createOptionAction(function (Action $action) {
                                    return $action
                                        ->modalHeading('Novo cliente')
                                        ->modalSubmitActionLabel('Cadastrar cliente')
                                        ->modalWidth('lg')
                                        ->closeModalByClickingAway(false);
                                })->columnSpan(2),

                            TextInput::make('customer_email')
                                ->label('Email')
                                ->hidden(fn (Get $get) => $get('customer_id') == null)
                                ->disabled()
                                ->columnSpan(1),

                            TextInput::make('customer_document')
                                ->label('CPF')
                                ->extraAlpineAttributes(['x-mask' => '999.999.999-99'])
                                ->hidden(fn (Get $get) => $get('customer_id') == null)
                                ->disabled()
                                ->columnSpan(1),

                            TextInput::make('customer_mobile')
                                ->label('Celular')
                                ->hidden(fn (Get $get) => $get('customer_id') == null)
                                ->disabled()
                                ->columnSpan(1),

                            TextInput::make('customer_birthdate')
                                ->label('Data Nascimento')
                                ->hidden(fn (Get $get) => $get('customer_id') == null)
                                ->disabled()
                                ->columnSpan(1),
                        ])
                        ->columnSpan(2),
                    Section::make()
                        ->schema([
                            Placeholder::make('total')
                                ->label('Últimas Compras')
                                ->content(function ($get) {
                                    if (is_null($get('customer_id'))) {
                                        return 'Nenhum cliente selecionado';
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
                        ->columnSpan(2)
                        ->schema([
                            ToggleButtons::make('payment_method')
                                ->label('Forma de Pagamento')
                                ->inline()
                                ->reactive()
                                ->required()
                                ->default(PaymentTypeEnum::CREDIT_CARD->value)
                                ->options(PaymentTypeEnum::class)
                                ->icons([
                                    'PIX' => 'fab-pix',
                                    'CREDIT_CARD' => 'heroicon-s-credit-card',
                                ]),

                            Fieldset::make('credit_card')
                                ->label('Cartão de Crédito')
                                ->visible(fn (Get $get): bool => $get('payment_method') === 'credit_card')
                                ->schema([
                                    TextInput::make('card_number')
                                        ->label('Número do Cartão de Crédito')
                                        ->default(fn (): string => app()->isLocal() ? '4444 4444 4444 4444' : '')
                                        ->required()
                                        ->extraAlpineAttributes(['x-mask' => '9999 999999 99999']),
                                    TextInput::make('name_on_card')
                                        ->label('Nome no Cartão')
                                        ->default(fn (): string => app()->isLocal() ? 'John Doe' : '')
                                        ->required(),
                                    TextInput::make('expiration_date')
                                        ->label('Validade')
                                        ->default(fn (): string => app()->isLocal() ? '12/24' : '')
                                        ->required()
                                        ->extraAlpineAttributes(['x-mask' => '99/99']),
                                    TextInput::make('cvv')
                                        ->label('CVV')
                                        ->default(fn (): string => app()->isLocal() ? '123' : '')
                                        ->required()
                                        ->extraAlpineAttributes(['x-mask' => '999']),
                                ]),

                            Fieldset::make('pix')
                                ->label('Pix')
                                ->visible(fn (Get $get): bool => $get('payment_method') === 'pix')
                                ->schema([
                                    ViewField::make('pix_button')
                                        ->view('orders.qr-code-generate')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Section::make()
                        ->columnSpan(1)
                        ->schema([
                            Placeholder::make('sale_resume')
                                ->label('Resumo do pedido')
                                ->content(function ($get) {
                                    return new HtmlString(
                                        view(
                                            view: 'orders.order-resume',
                                            data: ['items' => $get('items')]
                                        )->render()
                                    );
                                }),
                        ]),
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
                        $product = Product::find($state);
                        $set('name', $product->name ?? '');
                        $set('unit_price', $product->sale_price ?? 0);
                        $set('cost_price', $product->cost_price ?? 0);
                        $set('sub_total', $product->sale_price ?? 0);
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

                        return !is_null($stock) ? "{$stock} em estoque" : null;
                    })
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $stock = Product::find($get('product_id'))?->in_stock;

                        if($state > $stock) {
                            $state = $stock;
                            $set('quantity', $stock);
                        }
                        
                        $unit_price = $get('unit_price');

                        $sub_total = number_format((float)$state * $unit_price, 2, '.', '');

                        $set('sub_total', $sub_total);
                    })
                    ->disabled(fn (Get $get) => empty($get('name')))
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

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }
}
