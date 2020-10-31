<?php

use Illuminate\Database\Seeder;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reviews')->insert([
            'title' => 'Do not bother watching it',
            'review' => 'Below average movie with subpar acting and absolute rubbish direction',
            'rating' => '1',
            'upvotes' => '0',
            'downvotes' => '0',
            'deleted' => false,
            'movie_id' => '1',
            'user_id' => '1'
        ]);
    }
}
