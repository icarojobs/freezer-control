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
        $panel = PanelTypeEnum::APP;

        if (app()->isLocal() && $customer->email === 'admin@admin.com') {
            $password = 'password';
            $panel = PanelTypeEnum::ADMIN;
        }

        $user = User::create([
            'name' => $customer->name,
            'email' => $customer->email,
            'password' => bcrypt($password),
            'panel' => $panel,
        ]);

        $customer->user_id = $user->id;
        $customer->saveQuietly();

        Mail::to($customer->email)->send(new NewCustomerMail($customer, $password));
    }
}
