<?php

use Illuminate\Database\Seeder;

class PostStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_statuses')->insert([
            'status' => 'General',
            'code' => 'GN'
        ]);
    }
}
