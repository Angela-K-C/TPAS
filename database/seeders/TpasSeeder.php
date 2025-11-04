<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\Application;
use App\Models\Student;
use App\Models\Guest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker; //Manually initialize Faker

class TpasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Disable foreign key checks for mass insertion to prevent issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Create Base Users (1 admin, 10 students, 5 guests)
        
        // Create 1 main administrator for processing
        Administrator::factory()->count(1)->create(['email_address' => 'admin@tpas.edu', 'first_name' => 'System', 'role' => 'Superuser']);
        
        // Create 4 more random administrators
        Administrator::factory()->count(4)->create();

        // Create 10 students
        $students = Student::factory()->count(10)->create();

        // Create 5 guests
        $guests = Guest::factory()->count(5)->create();

        // 2. Create Applications and Passes for Students
        // Students have a higher chance of being approved (70% processed, 30% pending)
        $students->each(function (Student $student) use ($faker) {
            $numApps = $faker->numberBetween(1, 3);
            
            // Create a mix of processed and pending applications
            for ($i = 0; $i < $numApps; $i++) {
                $factory = Application::factory()
                    ->for($student, 'applicant');
                    
                // 70% chance to be processed, 30% pending (default)
                if ($faker->boolean(70)) {
                    $factory->processed();
                } else {
                    $factory->pending();
                }
                
                $factory->create();
            }
        });

        // 3. Create Applications for Guests
        // Guests have a 50% chance of being approved
        $guests->each(function (Guest $guest) use ($faker) {
            // Each guest makes 1 application
            $factory = Application::factory()
                ->count(1)
                ->for($guest, 'applicant');
            
            // 50% processed (with Pass), 50% pending
            if ($faker->boolean(50)) {
                $factory->processed();
            } else {
                $factory->pending();
            }
            
            $factory->create();
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
