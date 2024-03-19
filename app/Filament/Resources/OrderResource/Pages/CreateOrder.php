<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\OrderTransaction;
use Filament\Forms\Form;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Wizard;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use App\Services\PaymentGateway\Gateway;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Filament\Resources\OrderResource;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Pages\Concerns\HasWizard;
use App\Services\PaymentGateway\Connectors\AsaasConnector;

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
                    // ->startOnStep(3) // todo: remover após implementação do checkout
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false)
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction(
                        Action::make('create')
                            ->label('Finalizar pedido')
                            ->submit('create')
                            ->keyBindings(['mod+s'])
                    )

            ])
            ->columns(null);
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Cliente')
                ->icon('heroicon-m-user')
                ->description('Selecione o cliente')
                ->schema([
                    Section::make()
                        ->schema(
                            OrderResource::getCustomerFormDetails()
                        ),
                ]),
            Step::make('Itens do pedido')
                ->icon('heroicon-m-shopping-bag')
                ->description('Adicioine os itens ao pedido')
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
                ->icon('heroicon-s-credit-card')
                ->description('Selecione a forma de pagamento')
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



    protected function beforeCreate(): void
    {
    }

    protected function handleRecordCreation(array $data): Model
    {
        $chargeData = $this->prepareData($this->data);

        $gateway = self::Gateway();
        $payment = $gateway->payment()->create($chargeData);

        $creditCardData = $this->prepareCreditCardData($this->data);

        $response = $gateway->payment()->payWithCreditCard($payment['id'], $creditCardData);

        if ($response['status'] == 'CONFIRMED') {

            $data['status'] = OrderStatusEnum::PAID->value;
            $record = static::getModel()::create($data);
        } else {
            Notification::make()
                ->title('Erro ao processar pagamento')
                ->body('Não foi prossivel processar o pagamento. Tente novamente.')
                ->danger()
                ->send();
            throw new Halt();
        }


        //This need to be a job??!?!
        OrderTransaction::create([
            'order_id' => $record->id,
            'charge_id' => $response['id'],
            'billing_type' => $response['billingType'],
            'status' => $response['status'],
            'value' => $response['value'],
            'due_date' => $response['dueDate'],
            'description' => $response['description'],
            'installment_count' => 1, // Compra será parcelada? Senão, sempre será 1,
            'remote_ip' => $_SERVER['REMOTE_ADDR'], // Asaas não esta retornando o IP

        ]);


        return $record;
    }

    private function prepareData(array $data): array
    {
        return [
            "billingType" => $data['payment_method'],
            "customer" => $data['customer_external_id'],
            "dueDate" => now()->format('Y-m-d'),
            "value" => $data['total'],
            "description" => "Freezer Control",
            "daysAfterDueDateToCancellationRegistration" => 1,
            "postalService" => false,
        ];
    }

    private function prepareCreditCardData(array $data): array
    {
        return [
            "billingType" => $this->data['payment_method'],
            "customer" => $this->data['customer_external_id'],
            "creditCard" => [
                "holderName" => $this->data['name_on_card'],
                "number" => sanitize($this->data['card_number']),
                "expiryMonth" => explode("/", $this->data['expiration_date'])[0],
                "expiryYear" => explode("/", $this->data['expiration_date'])[1],
                "ccv" => $this->data['cvv']
            ],
            "creditCardHolderInfo" => [
                "name" => $this->data['customer_name'],
                "email" => $this->data['customer_email'],
                "cpfCnpj" => $this->data['customer_document'],
                "postalCode" => "89223-005", // TODO: this not saved on database
                "addressNumber" => "277",  // TODO: this not saved on database
                "addressComplement" => null,  // TODO: this not saved on database
                "mobilePhone" => sanitize($this->data['customer_mobile']),
            ],
        ];
    }

    private static function Gateway()
    {
        $adapter = app(AsaasConnector::class);
        $gateway = app(Gateway::class, ['adapter' => $adapter]);

        return $gateway;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Venda realizada com sucesso!';
    }
}
