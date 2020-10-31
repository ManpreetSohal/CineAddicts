<?php

use Illuminate\Database\Seeder;

class MoviesGenresAssociationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movies_genres_association')->insert([
            'movie_id' => '1',
            'genre_id' => '1',
        ]);
    }
}
