<?php

declare(strict_types=1);

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function beforeCreate(): void
    {

        $existingCustomer = Customer::where('email', $this->data['email'])
            ->orWhere('document', sanitize($this->data['document']))
            ->first();

        if ($existingCustomer) {
            if ($existingCustomer->email === $this->data['email'] || $existingCustomer->document === sanitize($this->data['document'])) {
                Notification::make('register_error')
                    ->title('Cadastro invalido!')
                    ->body('Seu E-mail ou CPF são invalidos!')
                    ->danger()
                    ->persistent()
                    ->send();

                $this->halt();
            }
        }
    }
}
