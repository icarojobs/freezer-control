<?php

namespace App\Filament\App;

use App\Enums\PanelTypeEnum;
use App\Filament\Forms\Components\PtbrMoney;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Pages\Dashboard as BaseDashboard;

class FreezerApp extends BaseDashboard implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $model = Product::class;

    protected static string $resource = OrderResource::class;
    protected static ?string $activeNavigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = "Meu freezer";
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.freezer-app';

    public Product $product;

    public ?array $data = [];

    public function mount(Product $product): void
    {
        $this->form->fill($product->toArray());
    }

    public function form(Form $form): Form
    {
        $product = Product::all();

        return $form
            ->schema([
                Repeater::make('items')
                    ->schema([
                        Select::make('product_id')
                            ->label('Produtos')
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
                    ])->statePath('data')
                    ->addActionLabel('Adicionar item')
                    ->maxItems($product->count())
                    ->columns(7)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query())//where('in_stock', '>', 0)
            ->columns([
                Stack::make([
                    ImageColumn::make('image')
                        ->height('100%')
                        ->width('100%'),
                    Stack::make([
                        TextColumn::make('name')
                            ->weight(FontWeight::ExtraBold)
                            ->alignCenter()->size(200),
                    ]),

                    Split::make([
                        TextColumn::make('sale_price')->prefix('R$ ')->size(90)->alignCenter(),

                    ])
                ])->space(1),
                    // Menu lateral opcional
                    //Panel::make([
                    //    Split::make([
                    //       TextColumn::make('in_stock')->suffix(' Un.'),
                    //       TextColumn::make('cost_price')->prefix('R$ '),
                    //    ]),
                    //])->collapsible(),
            ])
            ->contentGrid([
                'sm' => 1,
                'md' => 3,
                'lg' => 3,
                'xl' => 4,
                '2xl' => 4
            ])
            ->description('Aproveite as bebidas mais deliciosas de nosso freezer.')
            ->paginated([
                18,
                36,
                72,
                'all',
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('shopping-cart')
                    ->label(' Comprar')
                    ->button()
                    ->icon('heroicon-m-plus-circle')
                    ->color(Color::Teal)
                    ->url(fn(Product $record): string => '#' . urlencode($record->url)),
                ViewAction::make()->label('')
                    ->infolist(fn(Infolist $infolist) => ProductResource::infolist($infolist))
                    ->form(fn(Form $form) => ProductResource::form($form))->icon('heroicon-m-eye')->tooltip('Ver mais'),
                ActionGroup::make([
                    EditAction::make()->form(fn(Form $form) => ProductResource::form($form)),
                    DeleteAction::make(),
                ])->tooltip("Menu")->size(ActionSize::Small)->dropdownPlacement('top-middle')
                    ->hidden( ! auth()->user()->panel->value === PanelTypeEnum::ADMIN->value),

            ])
            ->bulkActions([
                // ...
            ]);
    }

    public static function getPages(): array
    {
        return [
            //https://filamentphp.com/docs/3.x/panels/resources/viewing-records#creating-another-view-page
            //https://filamentphp.com/docs/3.x/tables/layout#custom-html
        ];
    }

}
