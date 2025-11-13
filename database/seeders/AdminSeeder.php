<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'alvin.muriuki@strathmore.edu'],
            [
                'name' => 'Alvin Murithi',
                'password' => Hash::make('password'),
            ]
        );

        Admin::factory(14)->create();
    }
}
