<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('questions')->insert([
            [
                'question_details_id' => 1,
            ],
            [
                'question_details_id' => 2,
            ]
        ]);
    }
}
