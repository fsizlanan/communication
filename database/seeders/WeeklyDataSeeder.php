<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeeklyDataSeeder extends Seeder
{
    public function run(): void
    {
        $numIndividuals = 10;
        $numWeeks = 52;

        for ($i = 1; $i <= $numIndividuals; $i++) {
            for ($j = 1; $j <= $numWeeks; $j++) {
                DB::table('weekly_data')->insert([
                    'individual_id' => $i,
                    'week' => $j,
                    'value' => mt_rand() / mt_getrandmax(),
                ]);
            }
        }
    }
}
