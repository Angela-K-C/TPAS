<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExternalStudentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('external_university')->table('students')->insert([
            [
                'id' => 183523,
                'name' => 'Karanei Kimutai',
                'email' => 'kimutai.karanei@strathmore.edu',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'photo_url' => 'https://randomuser.me/api/portraits/women/1.jpg',
            ],
            [
                'id' => 190004,
                'name' => 'Witness Mukundi',
                'email' => 'mukundi.chingwena@strathmore.edu',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'photo_url' => 'https://randomuser.me/api/portraits/men/2.jpg',
            ],
            [
                'id' => 130103,
                'name' => 'Fatima Yusuf',
                'email' => 'fatima.yusuf@university.ac.ke',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'photo_url' => 'https://randomuser.me/api/portraits/women/3.jpg',
            ],
            [
                'id' => 130104,
                'name' => 'David Kariuki',
                'email' => 'david.kariuki@university.ac.ke',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'photo_url' => 'https://randomuser.me/api/portraits/men/4.jpg',
            ],
            [
                'id' => 130105,
                'name' => 'Chloe Wangari',
                'email' => 'chloe.wangari@university.ac.ke',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'photo_url' => 'https://randomuser.me/api/portraits/women/5.jpg',
            ],
            [
                'id' => 130106,
                'name' => 'Samuel Mwangi',
                'email' => 'samuel.mwangi@university.ac.ke',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'photo_url' => 'https://randomuser.me/api/portraits/men/6.jpg',
            ],
            [
                'id' => 189984,
                'name' => 'Alvin Murithi',
                'email' => 'alvin.muriuki@strathmore.edu',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'photo_url' => 'https://randomuser.me/api/portraits/men/18.jpg',
            ],
        ]);
    }
}
