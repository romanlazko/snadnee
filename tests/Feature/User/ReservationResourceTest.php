<?php

use App\Filament\Admin\Resources\ReservationResource;
use App\Filament\Admin\Resources\ReservationResource\Pages\CreateReservation;
use App\Filament\Admin\Resources\ReservationResource\Pages\ListReservations;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;

use function Pest\Livewire\livewire;

it('can render index page', function () {
    $this->get(ReservationResource::getUrl('index'))
        ->assertSuccessful();
});

it('can list reservations', function () {
    $user = User::factory()->create();

    $reservations = Reservation::factory()->count(10)->create([
        'user_id' => $user->id,
    ]);

    livewire(ListReservations::class)
        ->assertCanSeeTableRecords($reservations)
        ->assertCountTableRecords(10);
});

it('can render create page', function () {
    $this->get(ReservationResource::getUrl('create'))
        ->assertSuccessful();
});
