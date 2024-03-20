<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsaasWebhookController extends Controller
{

    public function __invoke(Request $request)
    {
        $environment = app()->isLocal() ? 'sandbox' : 'production';
        $token = config("asaas.{$environment}.webhook");

        $isValid = $request->header('asaas-access-token') === $token;

        if (!$isValid) return response('Unauthorized', 401);

        $data = $request->all();


        //Tio, para garantir a atomiciade da operação, se for o caso.
        DB::beginTransaction();

        try {
            //TODO atualizar o status do pedido na tabela orders



            //TODO registrar na table order_transations


            DB::commit(); // tudo deu certo, "commitamos"

        } catch (Throwable $th) {
            DB::rollBack(); // deu algo errado "desfazemos"
        }


        return response('OK', 200);
    }
}
