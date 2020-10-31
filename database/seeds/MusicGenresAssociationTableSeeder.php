<?php

use Illuminate\Database\Seeder;

class MusicGenresAssociationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('music_genres_association')->insert([
            'music_id' => '1',
            'genre_id' => '1',
        ]);
    }
}
