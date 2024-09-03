<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;
    protected static ?string $modelLabel = 'Fornecedores';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Logística';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('registered_name')
                            ->label('Razão social')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('name_company')
                            ->label('Fantasia')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('document')
                            ->label('Cnpj')
                            ->required()
                            ->mask('99.999.999/9999-99'),
                        Forms\Components\TextInput::make('ie')
                            ->label('Incrição estadual')
                            ->maxLength(14),
                        Forms\Components\TextInput::make('telephone')
                            ->label('Telefone')
                            ->mask('(99) 99999-9999')
                            ->tel()
                            ->maxLength(120),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(254)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document')
                    ->label('Documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registered_name')
                    ->label('Razão social')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_company')
                    ->label('Fantasia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telephone')
                    ->label('Telefone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }
}
