<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.auth.edit-profile';

    protected static string $layout = 'filament-panels::components.layout.index';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $profileData = [];

    public ?array $passwordData = [];

    public function mount(): void
    {
        $this->fillForms();
    }

    protected function getForms(): array
    {
        return [
            'editProfileForm',
            'editPasswordForm',
            'deleteAccountForm',
        ];
    }

    public function editProfileForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações do Perfil')
                    ->aside()
                    ->description('Atualize as informações do perfil da sua conta e o endereço de e-mail.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                    ]),
            ])
            ->model($this->getUser())
            ->statePath('profileData');
    }

    public function editPasswordForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Atualizar Senha')
                    ->aside()
                    ->description('Garanta que sua conta esteja usando uma senha longa e aleatória para se manter segura')
                    ->schema([
                        Forms\Components\TextInput::make('current_password')
                            ->label('Senha Atual')
                            ->password()
                            ->required()
                            ->currentPassword()
                            ->revealable(),
                        Forms\Components\TextInput::make('password')
                            ->label('Nova Senha')
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                            ->live(debounce: 500)
                            ->same('passwordConfirmation')
                            ->revealable(),
                        Forms\Components\TextInput::make('passwordConfirmation')
                            ->label('Confirmar Senha')
                            ->password()
                            ->required()
                            ->dehydrated(false)
                            ->revealable(),
                    ]),
            ])
            ->model($this->getUser())
            ->statePath('passwordData');
    }

    public function deleteAccountForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Excluir Conta')
                    ->description('Excluir permanentemente sua conta.')
                    ->aside()
                    ->schema([
                        Forms\Components\ViewField::make('deleteAccount')
                            ->label(__('Delete Account'))
                            ->hiddenLabel()
                            ->view('forms.components.delete-account-description'),
                        Actions::make([
                            Actions\Action::make('deleteAccount')
                                ->label('Excluir Conta')
                                ->icon('heroicon-m-trash')
                                ->color('danger')
                                ->requiresConfirmation()
                                ->modalHeading('Excluir Conta')
                                ->modalDescription('Tem certeza de que deseja excluir sua conta? Isso não pode ser desfeito!')
                                ->modalSubmitActionLabel('Sim, excluir!')
                                ->form([
                                    Forms\Components\TextInput::make('password')
                                        ->password()
                                        ->revealable()
                                        ->label('Senha')
                                        ->required(),
                                ])
                                ->action(function (array $data) {
                                    if (! Hash::check($data['password'], Auth::user()->password)) {
                                        $this->sendErrorDeleteAccount('Senha incorreta.');

                                        return;
                                    }

                                    auth()->user()?->update([
                                        'active' => false,
                                    ]);

                                    auth()->user()?->delete();
                                }),
                        ]),
                    ]),
            ])
            ->model($this->getUser())
            ->statePath('deleteAccountData');
    }

    protected function getUser(): Authenticatable&Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }

    protected function fillForms(): void
    {
        $data = $this->getUser()->attributesToArray();

        $this->editProfileForm->fill($data);
        $this->editPasswordForm->fill();
    }

    protected function getUpdateProfileFormActions(): array
    {
        return [
            Action::make('updateProfileAction')
                ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                ->submit('editProfileForm'),
        ];
    }

    protected function getUpdatePasswordFormActions(): array
    {
        return [
            Action::make('updatePasswordAction')
                ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                ->submit('editPasswordForm'),
        ];
    }

    public function updateProfile(): void
    {
        try {
            $data = $this->editProfileForm->getState();

            $this->handleRecordUpdate($this->getUser(), $data);
        } catch (Halt $exception) {
            return;
        }

        $this->sendSuccessNotification();
    }

    public function updatePassword(): void
    {
        try {
            $data = $this->editPasswordForm->getState();

            $this->handleRecordUpdate($this->getUser(), $data);
        } catch (Halt $exception) {
            return;
        }

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_'.Filament::getAuthGuard() => $data['password'],
            ]);
        }

        $this->editPasswordForm->fill();

        $this->sendSuccessNotification();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        unset($data['current_password']);
        $record->update($data);

        return $record;
    }

    private function sendSuccessNotification(): void
    {
        Notification::make()
            ->success()
            ->title(__('filament-panels::pages/auth/edit-profile.notifications.saved.title'))
            ->send();
    }

    private function sendErrorDeleteAccount(string $message): void
    {
        Notification::make()
            ->danger()
            ->title($message)
            ->send();
    }
}
