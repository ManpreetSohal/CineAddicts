<?php

use Illuminate\Database\Seeder;

class MoviesCompaniesAssociationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movies_companies_association')->insert([
            'movie_id' => '1',
            'company_id' => '1',
            'role_id' => '1',
        ]);
    }
}
