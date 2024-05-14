<?php

namespace App\Filament\App\Pages;

use App\Models\Product;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;

class MeuFreezer extends Page
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.meu-freezer';


}
