<?php

namespace App\Filament\App\Pages;

use App\Models\Product;
use App\Models\User;
use Filament\Pages\Dashboard as BasePage;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Page;
use Override;

class PainelUser extends Page
{
    protected static ?string $model = Product::class;

    protected static ?string $activeNavigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = "Meu painel";

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.painel-user';

    /**
     * @param Product $product;
     */
    public Product $product;

    public function mount(): void
    {
        //$this->product = Product::all();

        //dd(auth()->user());
    }

    #[Override]
    public function getFooter(): ?\Illuminate\Contracts\View\View
    {
        return view('partials/favicon');
    }
}
