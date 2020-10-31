<?php

use Illuminate\Database\Seeder;

class CompanyRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_roles')->insert([
            'role' => 'Producer ',
            'description' => 'Production company',
        ]);
    }
}
