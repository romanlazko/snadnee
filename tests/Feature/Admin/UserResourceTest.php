<?php

use App\Filament\Admin\Resources\UserResource;
use App\Filament\Admin\Resources\UserResource\Pages\CreateUser;
use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Filament\Admin\Resources\UserResource\Pages\ListUsers;
use App\Models\Reservation;
use App\Models\User;

use function Pest\Livewire\livewire;

it('can render index page', function () {
    $this->get(UserResource::getUrl('index'))
        ->assertSuccessful();
});

it('can list users', function () {
    $users = User::factory()->count(9)->create();

    $admin = User::where('email', 'admin@admin.com')->first();
    $users->prepend($admin);

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users);
});

it('can render create page', function () {
    $this->get(UserResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create', function () {
    $newData = User::factory()->make();

    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'name' => $newData->name,
        'email' => $newData->email,
    ]);
});

it('can validate input', function () {
    livewire(CreateUser::class)
        ->fillForm([
            'name' => null,
            'email' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
        ]);

    livewire(CreateUser::class)
        ->fillForm([
            'name' => str_repeat('a', 256),
            'email' => str_repeat('a', 256),
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'max:255',
            'email' => 'max:255',
        ]);

    livewire(CreateUser::class)
        ->fillForm([
            'name' => true,
            'email' => '-3dd23,.!',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'string',
            'email' => 'email',
        ]);
});

it('can render edit page', function () {
    $this->get(UserResource::getUrl('edit', [
            'record' => User::factory()->create(),
        ]))
        ->assertSuccessful();
});

it('can retrieve data', function () {
    $user = User::factory()->create();

    livewire(EditUser::class, [
            'record' => $user->getRouteKey(),
        ])
        ->assertFormSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

it('can save', function () {
    $user = User::factory()->create();
    $newData = User::factory()->make();

    livewire(EditUser::class, [
            'record' => $user->getRouteKey(),
        ])
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh())
        ->name->toBe($newData->name)
        ->email->toBe($newData->email);
});

it('can delete from table', function () {
    $user = User::factory()->create();
    $reservation = Reservation::factory()->create([
        'user_id' => $user->id,
    ]);

    livewire(ListUsers::class)
        ->callTableAction(Filament\Tables\Actions\DeleteAction::class, $user);

    $this->assertModelMissing($user);
    $this->assertModelMissing($reservation);
});

it('can delete from page', function () {
    $user = User::factory()->create();
    $reservation = Reservation::factory()->create([
        'user_id' => $user->id,
    ]);

    livewire(EditUser::class, [
            'record' => $user->getRouteKey(),
        ])
        ->callAction(Filament\Actions\DeleteAction::class);

    $this->assertModelMissing($user);
    $this->assertModelMissing($reservation);
});