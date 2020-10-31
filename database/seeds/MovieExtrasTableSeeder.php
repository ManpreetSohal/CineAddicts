<?php

use Illuminate\Database\Seeder;

class MovieExtrasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movie_extras')->insert([
            'extra' => 'The scene where the actor jumps from the plane was not performed by a stuntman but the actor himself',
            'upvotes' => '0',
            'downvotes' => '0',
            'verified' => false,
            'deleted' => false,
            'movie_id' => '1',
            'user_id' => '1'
        ]);
    }
}
