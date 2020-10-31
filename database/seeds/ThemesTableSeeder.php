<?php

use Illuminate\Database\Seeder;

class ThemesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('themes')->insert([
            'theme' => 'Default',
            'code' => 'DF'
        ]);

        DB::table('themes')->insert([
            'theme' => 'Dark',
            'code' => 'DK'
        ]);

        DB::table('themes')->insert([
            'theme' => 'Light',
            'code' => 'LG'
        ]);
    }
}
