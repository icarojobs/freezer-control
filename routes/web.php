<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('frontend.home');

Route::fallback(function () {
    return redirect('/app');
});
