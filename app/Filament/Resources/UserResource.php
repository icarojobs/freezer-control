<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\PanelTypeEnum;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $activeNavigationIcon = 'heroicon-o-user';

    protected static ?string $pluralModelLabel = "Usuários";
    protected static ?string $modelLabel = "Usuário";

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('panel')
                    ->label('Tipo de acesso')
                    ->options(PanelTypeEnum::class)
                    ->default('app')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->distinct(),

                Forms\Components\TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->description(function (User $record) {
                        return ($record->panel->value === "admin" ? 'Acesso administrativo':'acesso aplicativo');
                    })
                    ->searchable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('panel')
                    ->searchable()->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    Action::make('Editar tipo')
                        ->icon('heroicon-o-pencil-square')
                        ->form([
                            Select::make('panel')
                                ->options(PanelTypeEnum::class)
                                ->default(function (User $user){
                                    $panel = null;

                                    if($user->panel->value === 'admin'){
                                        $panel = PanelTypeEnum::ADMIN->value;
                                    }else{
                                        $panel = PanelTypeEnum::APP->value;
                                    }

                                    return $panel;
                                })
                        ])
                        ->action(function (User $user, array $data): void
                        {
                            $user->panel = $data['panel'];
                            $user->save();

                            Notification::make()
                                ->title('Alteração de status')
                                ->body("O tipo do usuário " . $user->name . " foi modificado com sucesso!")
                                ->icon('heroicon-o-shield-check')
                                ->color('success')
                                ->send();
                        }),

                ])
                    ->tooltip("Menu")
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }
}
