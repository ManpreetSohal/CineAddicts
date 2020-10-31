<?php

use Illuminate\Database\Seeder;

class MusicCompaniesAssociationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('music_companies_association')->insert([
            'music_id' => '1',
            'company_id' => '1',
            'role_id' => '1',
        ]);
    }
}
