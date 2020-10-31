<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'user1',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'user1@email.com',
            'phone_number' => '(123) 456-7890',
            'password' => bcrypt('password'),
        ]);
    }
}
