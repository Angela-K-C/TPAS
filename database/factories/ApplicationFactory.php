<?php

namespace Database\Factories;

use App\Models\Administrator;
use App\Models\Application;
use App\Models\Pass;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    /**
     * Define the model's default state (Pending).
     */
    public function definition(): array
    {
        // Default state (Pending)
        $validityDays = $this->faker->numberBetween(7, 365);
        $date = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'application_type' => $this->faker->randomElement(['Student Pass', 'Visitor Pass', 'Temporary Access']),
            'application_ref_id' => $this->faker->unique()->bothify('APP-#####-??'),
            'application_date' => $date,
            'reason' => $this->faker->sentence(5),
            'pass_validity_days' => $validityDays,
            'status' => 'Pending', // <-- Default Status is Pending
            'security_notes' => null,
            'processed_by_admin' => null,
            'pass_issued_date' => null,
            
            // These will be overridden by the seeder's `for()` call
            'applicant_type' => 'App\Models\Student', 
            'applicant_id' => 1,
        ];
    }
    
    /**
     * State for applications that have been processed and approved, automatically creating a Pass.
     * This method is now argument-free and chooses a random Administrator internally.
     */
    public function processed(): Factory
    {
        return $this->state(function (array $attributes) {
            // Fetch a random administrator's ID
            $admin = Administrator::inRandomOrder()->first();
            
            // Determine the date the application was processed (a few days after application date)
            $issuedDate = Carbon::parse($attributes['application_date'])->addDays($this->faker->numberBetween(1, 14));

            return [
                'status' => 'Processed',
                'processed_by_admin' => $admin->admin_id,
                'pass_issued_date' => $issuedDate,
                // Override ref_id format for processed passes
                'application_ref_id' => 'PASS-' . $this->faker->unique()->numberBetween(10000, 99999), 
            ];
        })->afterCreating(function (Application $application) {
            // After the application is created, create its associated Pass
            $application->pass()->create(Pass::factory()->make()->toArray());
        });
    }

    /**
     * State for applications that are specifically pending review.
     */
    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Pending',
                'processed_by_admin' => null,
                'pass_issued_date' => null,
            ];
        });
    }
}
