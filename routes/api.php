<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsaasWebhookController;

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// [POST] https://freezercontrol.com.br/api/asaas/webhook
// [POST] http://laravel.test/api/asaas/webhook


//FIXME
//  A biblioteca rupadana/filament-api-service adicionou o prefix api em todas as rotas, inclusive na rota api
// ficando dessa maneira: http://laravel.test/api/api/asaas/webhook


Route::post('/asaas/webhook', AsaasWebhookController::class)->middleware('asaas.webhook');
