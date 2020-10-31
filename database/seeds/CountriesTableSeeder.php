<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            'name' => 'Unknown',
            'iso_alpha_2_code' => 'UN',
            'iso_alpha_3_code' => 'UNK'
        ]);

        DB::table('countries')->insert([
            'name' => 'Canada',
            'iso_alpha_2_code' => 'CA',
            'iso_alpha_3_code' => 'CAN'
        ]);

        DB::table('countries')->insert([
            'name' => 'India',
            'iso_alpha_2_code' => 'IN',
            'iso_alpha_3_code' => 'IND'
        ]);
    }
}
