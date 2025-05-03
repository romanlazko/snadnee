<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\LoginResponse as AuthLoginResponse;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends AuthLoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->to(Filament::getPanel('admin')->getUrl());
        }

        return parent::toResponse($request);
    }
}