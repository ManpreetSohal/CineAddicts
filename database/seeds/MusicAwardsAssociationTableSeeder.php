<?php

use Illuminate\Database\Seeder;

class MusicAwardsAssociationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('music_awards_association')->insert([
            'year' => '2010',
            'music_id' => '1',
            'award_id' => '1',
            'role_id' => '1',
            'contributor_id' => '1'
        ]);
    }
}
