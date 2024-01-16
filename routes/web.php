<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('frontend.home');


Route::get('/customer-administration', function () {
    //return to_route('filament.auth.login');
})->name('login');
