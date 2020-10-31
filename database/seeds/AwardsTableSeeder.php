<?php

use Illuminate\Database\Seeder;

class AwardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('awards')->insert([
            'presenter' => 'The Academy Awards',
            'description' => 'he Academy Awards, also officially and popularly known as the Oscars, are awards for artistic and technical merit in the film industry',
            'website' => 'https://oscar.go.com/'
        ]);

        DB::table('awards')->insert([
            'presenter' => 'Iifa',
            'description' => 'Bollywood awards',
            'website' => 'https://www.iifa.com/'
        ]);
    }
}
