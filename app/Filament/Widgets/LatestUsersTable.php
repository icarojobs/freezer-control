<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestUsersTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Últimos usuários';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->limit(10)->with('customer'))
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('panel')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.mobile')
                    ->label('Celular')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.birthdate')
                    ->label('Data de Nascimento')
                    ->searchable()
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->date('d/m/Y H:i:s')
                    ->sortable()
            ]);
    }
}
