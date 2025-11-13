<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
    
class TemporaryPassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guests = \App\Models\Guest::all();
        $admins = \App\Models\Admin::all();
        \App\Models\TemporaryPass::factory(15)->make()->each(function ($pass) use ($guests, $admins) {
            $guest = $guests->random();
            $admin = $admins->random();
            $pass->passable_id = $guest->id;
            $pass->passable_type = get_class($guest);
            $pass->approved_by = $admin->id;
            $pass->save();
        });
    }
}
