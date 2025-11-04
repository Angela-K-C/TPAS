<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $program = $this->faker->randomElement([
            'Computer Science', 'Electrical Engineering', 'Business Management',
            'Art History', 'Applied Mathematics', 'Medicine'
        ]);

        return [
            // PK is default 'id'
            'first_name' => $firstName,
            'last_name' => $lastName,
            // UK: email_address
            'email_address' => $this->faker->unique()->safeEmail,
            // UK: mobile_number
            'mobile_number' => $this->faker->unique()->phoneNumber,
            'program_of_study' => $program,
        ];
    }
}
