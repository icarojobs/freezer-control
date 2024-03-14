<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\WebhookWrapper;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WebhookWrapperResource\Pages;
use App\Filament\Resources\WebhookWrapperResource\RelationManagers;

class WebhookWrapperResource extends Resource
{
    protected static ?string $model = WebhookWrapper::class;

    protected static ?string $modelLabel = "Webhook";

    protected static ?string $slug = "webhook";

    protected static ?string $navigationGroup = "Configurações";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('url')
                    ->label('URL')
                    ->required(),
                TextInput::make('authToken')
                    ->label('Token de Autenticação'),
                TextInput::make('email')
                    ->label('E-mail')
                    ->required(),
                Toggle::make('interrupted')
                    ->label('Interrompido')
                    ->onIcon('heroicon-m-bolt')
                    ->offIcon('heroicon-m-bolt')
                    ->onColor('success')
                    ->offColor('danger')
                    ->columnSpanFull(),
                Toggle::make('enabled')
                    ->label('Habilitado')
                    ->required()
                    ->onIcon('heroicon-m-bolt')
                    ->offIcon('heroicon-m-bolt')
                    ->onColor('success')
                    ->offColor('danger')
                    ->columnSpanFull()



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->recordUrl(null)
            ->columns([
                TextColumn::make('url')
                    ->label('URL'),
                TextColumn::make('email')
                    ->label('E-mail'),
                ToggleColumn::make('interrupted')
                    ->label('Interrompido'),
                ToggleColumn::make('enabled')
                    ->label('Habilitado'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListWebhookWrappers::route('/'),
            'create' => Pages\CreateWebhookWrapper::route('/create'),
            'edit' => Pages\EditWebhookWrapper::route('/{record}/edit'),
        ];
    }
}
