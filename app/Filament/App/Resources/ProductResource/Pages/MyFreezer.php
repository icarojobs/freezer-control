<?php

namespace App\Filament\App\Resources\ProductResouceResource\Pages;

use App\Filament\App\Resources\ProductResouceResource;
use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\Page;

class MyFreezer extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.app.resources.product-resource.pages.my-freezer';
}
