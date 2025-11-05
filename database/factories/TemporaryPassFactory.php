<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TemporaryPass>
 */
class TemporaryPassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'passable_id' => 1, // Should be set properly in seeder for real relations
            'passable_type' => \App\Models\Guest::class,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'reason' => $this->faker->sentence(),
            'qr_code_token' => $this->faker->unique()->uuid(),
            'qr_code_path' => null,
            'valid_from' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'valid_until' => $this->faker->dateTimeBetween('now', '+1 week'),
            'approved_by' => null, // Should be set in seeder if needed
        ];
    }
}
