<?php

use Illuminate\Database\Seeder;

class UserStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_statuses')->insert([
            'status' => 'Active',
            'code' => 'AC'
        ]);

        DB::table('user_statuses')->insert([
            'status' => 'Inactive',
            'code' => 'IN'
        ]);
    }
}
