<?php

use App\Http\Controllers\Frontend\QrCodeController;
use App\Models\Customer;
use App\Services\AsaasPhp\Customer\CreateCustomerFromModel;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/qr-code')->name('frontend.home');

Route::get('/qr-code', QrCodeController::class)
    ->name('frontend.qr-code');

Route::get('teste', function () {
    $teste = 123;

    dd($teste);
    //$customer = Customer::find(7);

    //(new CreateCustomerFromModel($customer))->send();
});

Route::fallback(function () {
    return redirect('/app');
})->name('login');
