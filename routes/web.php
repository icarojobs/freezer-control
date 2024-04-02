<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('frontend.qr-code');
})->name('frontend.home');

Route::get('/qr-code', App\Http\Controllers\Frontend\QrCodeController::class)
    ->name('frontend.qr-code');

Route::fallback(function () {
    return redirect('/app');
})->name('login');
