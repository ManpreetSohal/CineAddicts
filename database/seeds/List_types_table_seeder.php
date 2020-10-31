<?php

use Illuminate\Database\Seeder;

class List_types_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('List_types')->insert([
            'type' => 'Seen List'
        ]);

        DB::table('List_types')->insert([
            'type' => 'Watch List'
        ]);

        DB::table('List_types')->insert([
            'type' => 'Other'
        ]);
    }
}
