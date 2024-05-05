<?php

namespace App\Filament\App\Pages;

use App\Filament\Forms\Components\PtbrMoney;
use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as BasePage;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Page;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class PainelUser extends Page
{
    protected static ?string $model = Product::class;

    protected static ?string $activeNavigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = "Meu painel";

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.painel-user';

    /**
     * @param Product $product;
     */
    public Product $product;

    public ?array $data = [];

    public function mount(): void
    {

    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
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
}
