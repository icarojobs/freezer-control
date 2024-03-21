<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AsaasAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $environment = app()->isLocal() ? 'sandbox' : 'production';
        $token = config("asaas.{$environment}.webhook");

        $isValid = $request->header('asaas-access-token') === $token;

        if (!$isValid) return response('Unauthorized', 401);

        return $next($request);
    }
}
