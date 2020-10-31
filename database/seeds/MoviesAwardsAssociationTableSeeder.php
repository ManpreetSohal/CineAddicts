<?php

use Illuminate\Database\Seeder;

class MoviesAwardsAssociationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movies_awards_association')->insert([
            'year' => '2010',
            'movie_id' => '1',
            'award_id' => '1',
            'role_id' => '1',
            'contributor_id' => '1'
        ]);
    }
}
