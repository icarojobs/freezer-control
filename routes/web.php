<?php

use App\Http\Controllers\Frontend\QrCodeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/qr-code')->name('frontend.home');

Route::get('/qr-code', QrCodeController::class)
    ->name('frontend.qr-code');

Route::fallback(function () {
    return redirect('/app');
})->name('login');
