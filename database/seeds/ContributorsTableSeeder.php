<?php

use Illuminate\Database\Seeder;

class ContributorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contributors')->insert([
            'first_name' => 'Christian ',
            'last_name' => 'Bale',
            'stage_name' => 'Christian Bale',
            'date_of_birth' => '1974-01-30',
            'place_of_birth' => 'Pembrokeshire, Wales, UK',
            'biography' => 'Coming soon',
            'number_of_followers' => '10000'
        ]);
    }
}
