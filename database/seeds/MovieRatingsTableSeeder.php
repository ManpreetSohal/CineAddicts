<?php

use Illuminate\Database\Seeder;

class MovieRatingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movie_ratings')->insert([
            'rating' => 'G',
            'description' => 'General'
        ]);

        DB::table('movie_ratings')->insert([
            'rating' => 'PG',
            'description' => 'Parental guidance suggested'
        ]);

        DB::table('movie_ratings')->insert([
            'rating' => 'R',
            'description' => 'Restricted'
        ]);
    }
}
