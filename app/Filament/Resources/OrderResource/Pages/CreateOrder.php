<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Product;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\OrderResource;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\Quantity\Components\Quantity;
use App\Filament\Forms\Components\PtbrMoney;
use Filament\Resources\Pages\Concerns\HasWizard;

class CreateOrder extends CreateRecord
{
    use HasWizard;

    protected static string $resource = OrderResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Cliente')
                ->schema([
                    Section::make()
                        ->schema(
                            OrderResource::getCustomerFormDetails()
                        ),
                ]),
            Step::make('Itens do pedido')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('customer_name')
                                ->label('Cliente')
                                ->disabled()
                                ->columnSpan(1),
                            TextInput::make('total')
                                ->label('Valor total da compra')
                                ->disabled()
                                ->prefix('R$')
                                ->placeholder(function ($get) {
                                    $fields = $get('items');
                                    $sum = 0.0;
                                    foreach ($fields as $field) {

                                        $sum += floatval($field['sub_total']);
                                    }
                                    return number_format($sum, 2, '.', '');
                                })
                                ->columnSpan(1),
                            Section::make()
                                ->schema([
                                    OrderResource::getItemsRepeater()
                                ]),
                        ])

                ]),

            Step::make('Pagamento')
                ->schema([
                    Section::make()
                        ->schema(OrderResource::getPaymentFormDetails()),
                ]),
        ];
    }
}
