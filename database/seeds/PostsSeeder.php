<?php

use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            'title' => 'Test Post',
            'content' => '<h2>Post content</h2>',
            'date_of_publication' => 'General',
            'user_id' => 1,
            'category_id' => 1,
            'status_id' => 1,
            'tags_id' => 1,
            'comments_allowed' => 1,
        ]);
    }
}
