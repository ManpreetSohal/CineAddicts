<?php

use Illuminate\Database\Seeder;

class MoviesContributorsAssociationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movies_contributors_association')->insert([
            'movie_id' => '1',
            'contributor_id' => '1',
            'role_id' => '1'
        ]);
    }
}
