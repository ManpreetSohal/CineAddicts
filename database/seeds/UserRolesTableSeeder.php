<?php

use Illuminate\Database\Seeder;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            'role' => 'Administrator',
            'code' => 'AD'
        ]);

        DB::table('user_roles')->insert([
            'role' => 'Editor',
            'code' => 'ED'
        ]);

        DB::table('user_roles')->insert([
            'role' => 'Subscriber',
            'code' => 'SB'
        ]);
    }
}
