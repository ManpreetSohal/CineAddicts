<?php

use Illuminate\Database\Seeder;

class ContributorRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contributor_roles')->insert([
            'role' => 'Lead Actor',
            'description' => 'Plays the role of a leading actor in a movie'
        ]);

        DB::table('contributor_roles')->insert([
            'role' => 'Supporting Actor',
            'description' => 'Plays the role of a supporting actor in a movie'
        ]);
    }
}
