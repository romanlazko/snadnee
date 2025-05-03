<?php

namespace App\Filament\Admin\Resources\ReservationResource\Pages;

use App\Filament\Admin\Resources\ReservationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    protected ?bool $hasDatabaseTransactions = true;
    
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['table_id'] = $data['table'];
        $data['user_id'] = $data['user'];

        return $data;
    }
}
