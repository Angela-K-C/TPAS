<?php

namespace Database\Factories;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GuestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Guest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $idType = $this->faker->randomElement(['Passport', 'Driver License', 'National ID']);

        return [
            // PK: guest_id (string)
            'guest_id' => 'GUEST-' . $this->faker->unique()->randomNumber(5),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            // UK: email_address
            'email_address' => $this->faker->unique()->safeEmail,
            // UK: mobile_number
            'mobile_number' => $this->faker->unique()->phoneNumber,
            'identification_type' => $idType,
            // UK: identification_number
            'identification_number' => $this->faker->unique()->bothify('??#########'),
            'visit_purpose' => $this->faker->realText(100),
            'guest_photo_url' => $this->faker->boolean(50) 
                                 ? 'https://placehold.co/400x400/1e293b/cbd5e1?text=Guest+Photo' 
                                 : null,
        ];
    }
}
