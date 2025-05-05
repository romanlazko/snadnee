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
    $reservations = Reservation::factory()->count(10)->create();

    livewire(ListReservations::class)
        ->assertCanSeeTableRecords($reservations)
        ->assertCountTableRecords(10);
});

it('can render create page', function () {
    $this->get(ReservationResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create', function () {
    $newData = Reservation::factory()->make();

    livewire(CreateReservation::class)
        ->fillForm([
            'date' => $newData->date->format('Y-m-d'),
            'time' => $newData->time,
            'table' => $newData->table->id,
            'number_of_people' => $newData->number_of_people,
            'user' => $newData->user->id,
            'phone' => $newData->phone,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Reservation::class, [
        'date' => $newData->date,
        'time' => $newData->time,
        'table_id' => $newData->table->id,
        'number_of_people' => $newData->number_of_people,
        'user_id' => $newData->user->id,
        'phone' => $newData->phone,
    ]);
});

it('can validate input', function () {
    livewire(CreateReservation::class)
        ->fillForm([
            'date' => null,
            'time' => null,
            'table' => null,
            'number_of_people' => null,
            'user' => null,
            'phone' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'date' => 'required',
            'time' => 'required',
            'table' => 'required',
            'number_of_people' => 'required',
            'user' => 'required',
            'phone' => 'required',
        ]);

    livewire(CreateReservation::class)
        ->fillForm([
            'date' => now()->subDay()->format('Y-m-d'),
            'time' => '18:00',
            'table' => 100,
            'number_of_people' => 10,
            'user' => 100,
            'phone' => 'string',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'date' => 'after_or_equal',
            'table' => 'exists',
            'user' => 'exists',
            'phone'
        ]);

    $reservation = Reservation::factory()->create();

    livewire(CreateReservation::class)
        ->fillForm([
            'date' => $reservation->date->format('Y-m-d'),
            'time' => $reservation->time,
            'table' => $reservation->table->id,
            'number_of_people' => 10,
            'user' => $reservation->user->id,
            'phone' => $reservation->phone,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'table',
        ]);
});

it('can delete from table', function () {
    $reservation = Reservation::factory()->create();

    livewire(ListReservations::class)
        ->callTableAction(Filament\Tables\Actions\DeleteAction::class, $reservation);

    $this->assertModelMissing($reservation);
});