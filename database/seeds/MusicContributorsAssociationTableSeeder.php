<?php

use Illuminate\Database\Seeder;

class MusicContributorsAssociationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('music_contributors_association')->insert([
            'music_id' => '1',
            'contributor_id' => '1',
            'role_id' => '1'
        ]);
    }
}
