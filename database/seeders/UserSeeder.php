<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_data = [[
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin_password') 
        ]];

        $data = [
            ['name' => 'Chloé Hoareau'], ['name' => 'Martin Payet'], ['name' => 'Jean Albert']
        ];

        DB::table('users')->insert($admin_data);
        DB::table('users')->insert($data);
    }
}
