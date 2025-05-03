<?php

namespace App\Filament\User\Resources\ReservationResource\Pages;

use App\Filament\User\Resources\ReservationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id;
        $data['table_id'] = $data['table'];

        return $data;
    }
}
