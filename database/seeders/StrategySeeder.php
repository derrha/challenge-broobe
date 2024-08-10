<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrategySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $strategies = [
                'DESKTOP',
                'MOBILE'
            ];

            foreach ($strategies as $strategy) {
                DB::table('strategies')->insert([
                    'name' => $strategy,
                ]);
            }
        }
    }
}
