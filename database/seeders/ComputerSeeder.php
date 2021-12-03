<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComputerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [['name' => 'Ordinateur 1'], ['name' => 'Ordinateur 2'], ['name' => 'Ordinateur 3']];
        DB::table(('computers'))->insert($data);
    }
}
