<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $passes = \App\Models\TemporaryPass::all();
        \App\Models\EmailLog::factory(15)->make()->each(function ($log) use ($passes) {
            $pass = $passes->random();
            $log->temporary_pass_id = $pass->id;
            $log->recipient_email = $pass->passable_type::find($pass->passable_id)->email ?? $log->recipient_email;
            $log->save();
        });
    }
}
