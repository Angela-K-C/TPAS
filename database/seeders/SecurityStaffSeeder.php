<?php

/**
 * Security bundle copy:
 * Seeds a few default guard accounts (password defaults to `password`).
 */

namespace Database\Seeders;

use App\Models\SecurityStaff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SecurityStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = [
            ['name' => 'Command Desk', 'email' => 'security@tpas.com'],
            ['name' => 'Gate A Guard', 'email' => 'gate.a@tpas.com'],
            ['name' => 'Gate B Guard', 'email' => 'gate.b@tpas.com'],
            ['name' => 'Alvin Murithi', 'email' => 'alvin.muriuki@strathmore.edu'],
        ];

        foreach ($staff as $member) {
            SecurityStaff::updateOrCreate(
                ['email' => $member['email']],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
