<?php

namespace Database\Factories;

use App\Models\Administrator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdministratorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Administrator::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $role = $this->faker->randomElement(['Supervisor', 'Manager', 'Clerk']);

        return [
            // PK: admin_id (string)
            'admin_id' => 'ADMIN-' . $this->faker->unique()->randomNumber(5),
            // UK: email_address
            'email_address' => Str::lower($firstName) . '.' . Str::lower($lastName) . '@tpas.edu',
            'first_name' => $firstName,
            'role' => $role,
        ];
    }
}
