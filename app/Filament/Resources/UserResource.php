<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\PanelTypeEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-o-user';

    protected static ?string $slug = 'usuarios';

    protected static ?string $modelLabel = 'Usuário';

    protected static ?string $pluralModelLabel = 'Usuários';

    protected static ?string $recordTitleAttribute = 'name';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Foo Bar'),
                        Forms\Components\TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('foo@bar.com'),

                        Forms\Components\TextInput::make('password')
                            ->label('Senha')
                            ->password()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\ToggleButtons::make('panel')
                            ->label('Tipo de acesso')
                            ->options(PanelTypeEnum::class)
                            ->default(PanelTypeEnum::APP)
                            ->inline()
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->description(fn(User $record) => $record->panel->value === 'admin' ? 'Acesso administrativo' : 'acesso aplicativo'),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('panel')
                    ->searchable()
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    DeleteAction::make()
                        ->action(function(User $record){
                            if ($record->customer == null) {
                                static::notificationDelete($record);
                            } else {
                                if($record->customer->orders->isEmpty()){
                                    static::notificationDelete($record);
                                    return null;
                                }
                                Notification::make()
                                    ->title('Não é possível excluir este usuário!')
                                    ->body("Este usuário tem pedidos associados em andamento.")
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Deletar '. $table->getModelLabel())
                        ->modalDescription('Tem certeza de que deseja excluir este '. $table->getModelLabel() .'? Isto não pode ser desfeito.')
                        ->modalSubmitActionLabel('Sim, deletar!'),

                    Action::make('Editar tipo')
                        ->icon('heroicon-o-pencil-square')
                        ->form([
                            Select::make('panel')
                                ->options(PanelTypeEnum::class)
                                ->default(fn(User $user): string => $user->panel->value === 'admin' ? PanelTypeEnum::ADMIN->value : PanelTypeEnum::APP->value)
                        ])
                        ->action(function (User $user, array $data): void {
                            $user->panel = $data['panel'];
                            $user->save();

                            Notification::make()
                                ->title('Alteração de status')
                                ->body('O tipo do usuário ' . $user->name . ' foi modificado com sucesso!')
                                ->icon('heroicon-o-shield-check')
                                ->color('success')
                                ->send();
                        }),
                ])->tooltip('Menu')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewUser::class,
            Pages\EditUser::class,
        ]);
    }
  
    public static function notificationDelete(User $record): void
    {
        $record->delete();

        Notification::make()
            ->title('Excluído com sucesso!')
            ->body("Usuário removido da sua carteira de clientes!")
            ->success()
            ->send();
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string)static::getModel()::count();
    }
}
