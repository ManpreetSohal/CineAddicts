<?php

use Illuminate\Database\Seeder;

class MusicGenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('music_genres')->insert([
            'genre' => 'pop',
            'description' => 'pop music'
        ]);

        DB::table('music_genres')->insert([
            'genre' => 'rap',
            'description' => 'rap music'
        ]);
    }
}
