<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Forms\Components\Fieldset;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    public function filtersForm(Form $form)
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Fieldset::make('label')
                            ->label('Filtro por intervalo de Datas')
                            ->schema([
                                DatePicker::make('startDate')
                                    ->label('')
                                    ->prefix('Data inicial')
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->closeOnDateSelection()
                                    ->placeholder('Selecione uma data')
                                    ->maxDate(fn (Get $get) => $get('endDate') ?: now()),
                                DatePicker::make('endDate')
                                    ->label('')
                                    ->prefix('Data final')
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->placeholder('Selecione uma data')
                                    ->closeOnDateSelection()
                                    ->maxDate(now())
                                    ->minDate(fn (Get $get) => $get('startDate') ?: false),
                            ])->columns(3),
                    ])
            ]);
    }
}
