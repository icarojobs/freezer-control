<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ---------------- PAINEL ADMIN --------------------------
// Cadastro de usuário (sem autenticação)
// Login de usuário (sem autenticação)
// Informações de dashboard - mais informações do que no painel /app (logado)
// Atualização cadastral de clientes [customer] (nome, email [caso não exista], celular
//  - Já atualizar o email dele também ta tabela users (caso aplicável)

// Inventário (logado)
// Venda (venda)


// ---------------- PAINEL APP --------------------------
// Cadastro de usuário (sem autenticação)
// Login de usuário (sem autenticação)
// Informações de dashboard (logado)
// Atualização cadastral [customer] (nome, email [caso não exista], celular
//  - Já atualizar o email dele também ta tabela users (caso aplicável)
