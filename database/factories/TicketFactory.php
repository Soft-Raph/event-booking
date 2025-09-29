<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'type' => $this->faker->randomElement(['VIP','Standard','Student']),
            'price' => $this->faker->randomFloat(2, 10, 300),
            'quantity' => $this->faker->numberBetween(10, 200),
        ];
    }
}
