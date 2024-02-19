<?php

namespace App\Http\Responses;

use App\Enums\PanelTypeEnum;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{

    public function toResponse($request): RedirectResponse|Redirector
    {


        if ($request->user()->panel === PanelTypeEnum::ADMIN) {

            return redirect()->route("filament.{$request->user()->panel->value}.pages.dashboard");

        }


        return parent::toResponse($request);
    }
}
