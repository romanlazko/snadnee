<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\LogoutResponse as AuthLogoutResponse;
use Illuminate\Http\RedirectResponse;

class LogoutResponse extends AuthLogoutResponse
{
    public function toResponse($request): RedirectResponse
    {
        return redirect()->to('/');
    }
}