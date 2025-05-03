<?php

use App\Filament\Admin\Resources\TableResource;
use App\Filament\Admin\Resources\TableResource\Pages\CreateTable;
use App\Filament\Admin\Resources\TableResource\Pages\EditTable;
use App\Filament\Admin\Resources\TableResource\Pages\ListTables;
use App\Models\Reservation;
use App\Models\Table;

use function Pest\Livewire\livewire;

it('can render index page', function () {
    $this->get(TableResource::getUrl('index'))
        ->assertSuccessful();
});

it('can list tables', function () {
    $tables = Table::factory()->count(10)->create();

    livewire(ListTables::class)
        ->assertCanSeeTableRecords($tables)
        ->assertCountTableRecords(10);
});

it('can render create page', function () {
    $this->get(TableResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create', function () {
    $newData = Table::factory()->make();

    livewire(CreateTable::class)
        ->fillForm([
            'name' => $newData->name,
            'description' => $newData->description,
            'seat_count' => $newData->seat_count,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Table::class, [
        'name' => $newData->name,
        'description' => $newData->description,
        'seat_count' => $newData->seat_count,
    ]);
});

it('can validate input', function () {
    livewire(CreateTable::class)
        ->fillForm([
            'name' => null,
            'description' => null,
            'seat_count' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'seat_count' => 'required',
        ]);

    livewire(CreateTable::class)
        ->fillForm([
            'name' => str_repeat('a', 256), // точно 256 символов
            'description' => str_repeat('a', 256),
            'seat_count' => 300,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'max:255',
            'description' => 'max:255',
            'seat_count' => 'max:255',
        ]);

    livewire(CreateTable::class)
        ->fillForm([
            'name' => str_repeat('a', 2), // точно 256 символов
            'description' => str_repeat('a', 256),
            'seat_count' => 0,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'min:3',
            'seat_count' => 'min:1',
        ]);
});

it('can render edit page', function () {
    $this->get(TableResource::getUrl('edit', [
            'record' => Table::factory()->create(),
        ]))
        ->assertSuccessful();
});

it('can retrieve data', function () {
    $table = Table::factory()->create();

    livewire(EditTable::class, [
            'record' => $table->getRouteKey(),
        ])
        ->assertFormSet([
            'name' => $table->name,
            'description' => $table->description,
            'seat_count' => $table->seat_count,
        ]);
});

it('can save', function () {
    $table = Table::factory()->create();
    $newData = Table::factory()->make();

    livewire(EditTable::class, [
            'record' => $table->getRouteKey(),
        ])
        ->fillForm([
            'name' => $newData->name,
            'description' => $newData->description,
            'seat_count' => $newData->seat_count,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($table->refresh())
        ->name->toBe($newData->name)
        ->description->toBe($newData->description)
        ->seat_count->toBe($newData->seat_count);
});

it('can delete from table', function () {
    $table = Table::factory()->create();
    $reservation = Reservation::factory()->create([
        'table_id' => $table->id,
    ]);

    livewire(ListTables::class)
        ->callTableAction(Filament\Tables\Actions\DeleteAction::class, $table);

    $this->assertModelMissing($table);
    $this->assertModelMissing($reservation);
});

it('can delete from edit page', function () {
    $table = Table::factory()->create();
    $reservation = Reservation::factory()->create([
        'table_id' => $table->id,
    ]);

    livewire(EditTable::class, [
            'record' => $table->getRouteKey(),
        ])
        ->callAction(Filament\Actions\DeleteAction::class);

    $this->assertModelMissing($table);
    $this->assertModelMissing($reservation);
});