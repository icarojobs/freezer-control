<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use App\Models\Customer;
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;
use Closure;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DomainException;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register;
use Filament\Support\RawJs;
use Illuminate\Support\Str;
use Override;

class FreezerControlRegister extends Register
{
    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome Completo')
                    ->required()
                    ->maxLength(255),

                TextInput::make('document')
                    ->label('CPF')
                    ->required()
                    ->unique()
                    ->mask(RawJs::make(<<<'JS'
                        '999.999.999-99'
                    JS
                    ))
                    ->rule('cpf_ou_cnpj'),

                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->maxLength(255),

                TextInput::make('mobile')
                    ->label('Whatsapp')
                    ->mask('(99) 99999-9999')
                    ->required()
                    ->maxLength(15),

                DatePicker::make('birthdate')
                    ->label('Data de Nascimento')
                    ->rules([
                        function () {
                            return function (string $attribute, $value, Closure $fail) {
                                if (now()->parse($value)->age < 18) {
                                    $fail('A data de nascimento deve ser maior que 18 anos.');
                                }
                            };
                        }
                    ])
                    ->required(),
            ]);
    }

    #[Override]
    public function register(): ?RegistrationResponse
    {
        $this->rateLimit(2);

        $this->data = $this->form->getState();

        try {
            $adapter = new AsaasConnector();
            $gateway = new Gateway($adapter);

            $data = [
                'name' => $this->data['name'],
                'cpfCnpj' => sanitize($this->data['document']),
                'email' => $this->data['email'],
                'mobilePhone' => sanitize($this->data['mobile']),
            ];

            $customer = $gateway->customer()->create($data);

            if (!isset($customer['id'])) {
                $message = str('')
                    ->append("Não foi possível criar o ID do Cliente: ")
                    ->append($customers['errors'][0]['description'] ?? 'Erro inesperado')
                    ->toString();

                Notification::make('register_error')
                    ->title('Erro ao criar cliente')
                    ->body($message)
                    ->danger()
                    ->persistent()
                    ->send();

                return null;
            }
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        } catch (\DomainException|\Exception $domainException) {
            Notification::make()
                ->title('Erro ao realizar cadastro')
                ->body($domainException->getMessage())
                ->warning()
                ->send();

            return null;
        }

        $this->data['customer_id'] = $customer['id'];
        Customer::create($this->data);

        Notification::make()
            ->title('Cadastro Realizado!')
            ->body("Enviamos um email para {$this->data['email']} com seus dados de acesso.")
            ->success()
            ->send();

        $this->reset();

        return null;
    }
}
