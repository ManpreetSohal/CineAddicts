<?php

use Illuminate\Database\Seeder;
use phpDocumentor\Reflection\Types\Null_;

class MusicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('music')->insert([
            'title' => 'Run this town',
            'release_date' => '2009-01-01',
            'length' => '189',
            'lyrics' => "Feel it comin' in the air (Yeah)
                        And the screams from everywhere (Yeah)
                        I'm addicted to the thrill (I'm ready)
                        It's a dangerous love affair (What's up, c'mon)
                        Can't be scared when it goes down
                        Got a problem, tell me now (What's up)
                        Only thing that's on my mind (Whats up)
                        Is who's gonna run this town tonight (What's up)
                        Is who's gonna run this town tonight (What's up)
                        We gonna run this town",
            'video_link' => '',
            'album_id' => null,
            'language_id' => '1'
        ]);
    }
}
