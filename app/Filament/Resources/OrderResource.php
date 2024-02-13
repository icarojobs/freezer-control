<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use LaraZeus\Quantity\Components\Quantity;
use App\Filament\Forms\Components\PtbrMoney;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\OrderResource\Pages;
use Filament\Forms\Components\Hidden;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $modelLabel = 'Venda';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static $total = 0.0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Group::make()
                    ->schema([
                        Section::make()
                            ->schema(static::getPaymentFormDetails()),
                        Placeholder::make('Resumo')
                            ->columns(2),
                    ]),
                Section::make()
                    ->schema([
                        Placeholder::make('Resumo')
                    ])


            ]);
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
                                ->options(Customer::all()->pluck('name', 'id'))
                                ->live(debounce: 250)
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state != null) {
                                        $customer = Customer::find($state);
                                        $set('customer_name', $customer->name);
                                        $set('customer_email', $customer->email);
                                        $set('customer_birthdate', $customer->birthdate->format('d/m/Y'));
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
                                        ->maxLength(255),

                                    DatePicker::make('birthdate')
                                        ->label('Data Nascimento')
                                        ->format('d/m/Y')
                                        ->required(),


                                ])
                                ->createOptionAction(function (Action $action) {
                                    return $action
                                        ->modalHeading('Novo cliente')
                                        ->modalSubmitActionLabel('Cadastrar cliente')
                                        ->modalWidth('lg')
                                        ->closeModalByClickingAway(false)
                                        ->action(fn (array $data) => static::saveCustomer($data) );
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
                            Placeholder::make('Ultimas compras')
                                ->content('Under construction'),
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
                            Placeholder::make('Formulário de pagamento')
                                ->content('Under construction'),
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
        return Repeater::make('items')
            ->schema([
                Select::make('product_id')
                    ->label('Item')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        $price = Product::find($state)?->sale_price;
                        $set('unit_price', $price ?? 0);
                        $set('quantity', 1);
                    })
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan(3),

                Quantity::make('quantity')
                    ->label('Quantidade')
                    ->reactive()
                    ->minValue(1)
                    ->default(1)
                    ->afterStateUpdated(function ($state, $set, $get) {

                        $sub_total = number_format((float) $state * $get('unit_price'), 2, '.', '');

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
            ->columns(7)
            ->columnSpanFull();
    }

    public static function saveCustomer(array $data)
    {
        Customer::create([
            "name" => $data['name'],
            "email" => $data['email'],
            "document" => $data['document'],
            "mobile" => $data['mobile'],
            "birthdate" => $data['birthdate'],
        ]);

        Notification::make('saved_customer')
            ->title('Cliente Registrado!')
            ->body("O cliente {$data['name']} foi salvo com sucesso!")
            ->success()
            ->send();
    }
}
