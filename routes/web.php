<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return to_route('filament.admin.auth.register');
})->name('frontend.home');

Route::fallback(function () {
    return redirect('/app');
});
