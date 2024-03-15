<?php
namespace App\Filament\Resources\CustomerResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\CustomerResource;
use Illuminate\Routing\Router;


class CustomerApiService extends ApiService
{
    protected static string | null $resource = CustomerResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
