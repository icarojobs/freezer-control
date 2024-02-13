<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\PanelTypeEnum;
use App\Mail\NewCustomerMail;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomerObserver
{
    public function created(Customer $customer): void
    {
        $password = Str::password(8);

        if (app()->isLocal() && $customer->email === 'admin@admin.com') {
            $password = 'password';
        }

        $user = User::create([
            'name' => $customer->name,
            'email' => $customer->email,
            'panel' => PanelTypeEnum::APP,
            'password' => bcrypt($password),
        ]);

        $customer->user_id = $user->id;
        $customer->saveQuietly();

        Mail::to($customer->email)->send(new NewCustomerMail($customer, $password));
    }
}
