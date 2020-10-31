<?php

use Illuminate\Database\Seeder;

class MovieGenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movie_genres')->insert([
            'genre' => 'Action',
            'description' => 'Contains action sequences, such as fighting, stunts, car chases or explosions, take precedence over elements like characterization or complex plotting'
        ]);

        DB::table('movie_genres')->insert([
            'genre' => 'Comedy',
            'description' => 'Contains comedic sequences'
        ]);

    }
}
