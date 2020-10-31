<?php

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            'language_original' => 'English',
            'language_english' => 'English',
            'code' => 'EN'
        ]);

        DB::table('languages')->insert([
            'language_original' => 'Français',
            'language_english' => 'French',
            'code' => 'FR'
        ]);

        DB::table('languages')->insert([
            'language_original' => 'ਪੰਜਾਬੀ',
            'language_english' => 'Punjabi',
            'code' => 'PA'
        ]);
    }
}
