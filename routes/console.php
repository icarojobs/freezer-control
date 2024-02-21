<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

Artisan::command('play', function () {
    $customers = (new App\Services\AsaasPhp\Customer\CustomerList)->list();

    dd($customers);
});
