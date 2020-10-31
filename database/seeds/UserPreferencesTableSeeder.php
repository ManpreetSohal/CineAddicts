<?php

use Illuminate\Database\Seeder;

class UserPreferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_preferences')->insert([
            'restricted_mode' => 0,
            'theme_id' => 1,
            'language_id' => 1
        ]);
    }
}
