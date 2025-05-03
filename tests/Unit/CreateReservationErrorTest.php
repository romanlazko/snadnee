<?php

use App\Models\Table;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('prevents double reservation on same table and datetime', function () {
    $table = Table::factory()->create();
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $dateTime = today();

    $data = [
        'table_id' => $table->id,
        'date' => $dateTime,
        'time' => '12:00',
        'phone' => '1234567890',
        'number_of_people' => '2',
    ];

    try {
        Reservation::create(array_merge($data, ['user_id' => $user2->id]));
        Reservation::create(array_merge($data, ['user_id' => $user1->id]));
    } catch (\Exception $e) {
        expect($e->getCode())->toBe('23000');
    }

    expect(Reservation::count())->toBe(1);
});
