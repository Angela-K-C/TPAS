<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmailLog>
 */
class EmailLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'temporary_pass_id' => 1, // Should be set properly in seeder for real relations
            'recipient_email' => $this->faker->unique()->safeEmail(),
            'subject' => $this->faker->sentence(6),
            'status' => $this->faker->randomElement(['sent', 'failed']),
            'error_message' => $this->faker->optional()->sentence(),
            'sent_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
