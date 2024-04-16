<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;


    protected function beforeCreate(): void
    {

        $existingCustomer = User::where('email', $this->data['email'])
            ->first();

        if ($existingCustomer && ($existingCustomer->email === $this->data['email'])) {
            Notification::make('register_error')
                ->title('Cadastro invalido!')
                ->body('Seu E-mail já foi registrado!')
                ->danger()
                ->persistent()
                ->send();

            $this->halt();
        }
    }
}
