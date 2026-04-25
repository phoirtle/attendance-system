<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        $clockIn  = fake()->dateTimeBetween('08:00:00', '09:30:00');
        $clockOut = Carbon::instance($clockIn)->addHours(fake()->numberBetween(7, 10));

        return [
            'user_id'            => User::factory(),
            'date'               => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'clock_in'           => $clockIn->format('H:i:s'),
            'clock_out'          => $clockOut->format('H:i:s'),
            'clock_in_latitude'  => -2.985 + fake()->randomFloat(4, -0.005, 0.005),
            'clock_in_longitude' => 104.732 + fake()->randomFloat(4, -0.005, 0.005),
            'distance_meters'    => fake()->numberBetween(5, 95),
            'photo_path'         => 'attendance_photos/fake_photo.jpg',
            'location_status'    => 'in_range',
            'status'             => fake()->randomElement(['present', 'present', 'present', 'late']),
        ];
    }

    public function late(): static
    {
        return $this->state(fn (array $attributes) => [
            'clock_in' => fake()->dateTimeBetween('09:01:00', '11:00:00')->format('H:i:s'),
            'status'   => 'late',
        ]);
    }

    public function absent(): static
    {
        return $this->state(fn (array $attributes) => [
            'clock_in'        => null,
            'clock_out'       => null,
            'location_status' => 'out_of_range',
            'status'          => 'absent',
        ]);
    }

    public function outOfRange(): static
    {
        return $this->state(fn (array $attributes) => [
            'clock_in_latitude'  => -6.2088,
            'clock_in_longitude' => 106.8456,
            'distance_meters'    => fake()->numberBetween(500, 100000),
            'location_status'    => 'out_of_range',
        ]);
    }
}
