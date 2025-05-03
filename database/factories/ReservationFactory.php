<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'table_id' => Table::factory(), 
            'date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'time' => $this->faker->time('H:i'),
            'phone' => $this->faker->e164PhoneNumber,
            'number_of_people' => $this->faker->numberBetween(1, 6),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Reservation $reservation) {
            $table = $reservation->table ?? Table::find($reservation->table_id);
            $reservation->number_of_people = fake()->numberBetween(1, $table->seat_count);
        })->afterCreating(function (Reservation $reservation) {
            $table = $reservation->table ?? Table::find($reservation->table_id);
            $reservation->number_of_people = fake()->numberBetween(1, $table->seat_count);
            $reservation->save();
        });
    }
}
