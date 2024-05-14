<?php

namespace App\Filament\App\Resources\OrderResource\Pages;

use App\Filament\App\Resources\OrderResource;
use Filament\Resources\Pages\Page;

class Carrinho extends Page
{
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.app.resources.order-resource.pages.carrinho';
}
