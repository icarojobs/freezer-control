<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $panelID = Filament\Facades\Filament::getPanel()->getId();

    return to_route("filament.{$panelID}.pages.dashboard");
})->name('frontend.home');

Route::get('/qr-code', App\Http\Controllers\Frontend\QrCodeController::class)
    ->name('frontend.qr-code');

Route::get('/teste', function () {
    $teste = 123;

    dd($teste);
});

Route::fallback(function () {
    return redirect('/app');
})->name('login');
