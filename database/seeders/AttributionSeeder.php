<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['horraire'=> '"[8, 10]"', 'date' => '"19/11/2021"', 'computer_id' => 1, 'user_id' =>  2],
            ['horraire'=> '"[11, 13]"', 'date' => '"19/11/2021"', 'computer_id' => 2, 'user_id' =>  3],
            ['horraire'=> '"[8, 10]"', 'date' => '"19/11/2021"', 'computer_id' => 3, 'user_id' =>  4],
            // ['horraire '=> '{"horraire": [11, 13]}', 'date' => '{date: "19/11/2021"}', 'computer_id' => 2, 'user_id' => 3], 
        ];

        DB::table('attributions')->insert($data);
    }
}
