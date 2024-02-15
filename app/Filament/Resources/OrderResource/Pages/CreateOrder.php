<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\OrderResource;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\Concerns\HasWizard;

class CreateOrder extends CreateRecord
{
    use HasWizard;

    protected static string $resource = OrderResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = [
            'customer_id' => $this->data['customer_id'],
            'items' => $this->data['items'],
            'total' => $this->data['total'],

        ];
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }



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
                            TextInput::make('placeholder_total')
                                ->label('Valor total da compra')
                                ->disabled()
                                ->prefix('R$')
                                ->placeholder(function ($get, $set) {
                                    $fields = $get('items');
                                    $sum = 0.0;
                                    foreach ($fields as $field) {
                                        $sum += floatval($field['sub_total']);
                                    }
                                    $sum = number_format($sum, 2, '.', '');
                                    $set('total', $sum);
                                    return $sum;
                                })
                                ->columnSpan(1),
                            Hidden::make('total')
                                ->default(0.0),
                            Section::make()
                                ->schema([
                                    OrderResource::getItemsRepeater()
                                ]),
                        ]),
                ]),

            Step::make('Pagamento')
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
                        ]),

                    Section::make()
                        ->schema(OrderResource::getPaymentFormDetails()),
                ]),
        ];
    }
}
