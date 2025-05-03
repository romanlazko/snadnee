<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Exception;
use Filament\Facades\Filament;
use Filament\Notifications\Auth\ResetPassword as ResetPasswordNotification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Password;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        $record = $this->getRecord();

        Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
            $record->attributesToArray(),
            function (User $user, string $token): void {
                if (! method_exists($user, 'notify')) {
                    $userClass = $user::class;

                    throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
                }

                $notification = app(ResetPasswordNotification::class, ['token' => $token]);
                $notification->url = Filament::getResetPasswordUrl($token, $user);

                $user->notify($notification);
            },
        );
    }
}
