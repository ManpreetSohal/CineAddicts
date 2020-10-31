<?php

use Illuminate\Database\Seeder;

class MoviesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movies')->insert([
            'title' => 'Shutter Island',
            'release_date' => '2010-12-01',
            'runtime' => '112',
            'poster_image_path' => '',
            'budget' => '20',
            'runtime' => '112',
            'synopsis' => 'On recommend tolerably my belonging or am. Mutual has cannot beauty indeed now sussex merely you. It possible no husbands jennings ye offended packages pleasant he. Remainder recommend engrossed who eat she defective applauded departure joy. Get dissimilar not introduced day her apartments. Fully as taste he mr do smile abode every. Luckily offered article led lasting country minutes nor old. Happen people things oh is oppose up parish effect. Law handsome old outweigh humoured far appetite. 
                           Be me shall purse my ought times. Joy years doors all would again rooms these. Solicitude announcing as to sufficient my. No my reached suppose proceed pressed perhaps he. Eagerness it delighted pronounce repulsive furniture no. Excuse few the remain highly feebly add people manner say. It high at my mind by roof. No wonder worthy in dinner.',
            'trailer_link' => 'https://www.youtube.com/watch?v=5iaYLCiq5RM',
            'streaming_link' => '',
            'rating_id' => '1',
            'language_id' => '1',
            //'region_id' => '1'

        ]);
    }
}
