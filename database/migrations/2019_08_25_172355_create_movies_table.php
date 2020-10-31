<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wiki_id')->unique();
            $table->string('title');
            $table->date('release_date')->nullable();
            $table->integer('runtime')->nullable();         // in minutes
            $table->string('poster_image_path')->nullable();
            $table->double('budget')->nullable();           // in millions
            $table->double('box_office')->nullable(); 
            $table->text('synopsis')->nullable();
            $table->string('trailer_link')->nullable();
            //$table->string('streaming_link')->nullable();
            //$table->unsignedBigInteger('rating_id');
            //$table->foreign('rating_id')->references('id')->on('movie_ratings');
           
            //$table->unsignedBigInteger('language_id');
            //$table->foreign('language_id')->references('id')->on('languages');
           // $table->unsignedBigInteger('country_id');
            //$table->foreign('country_id')->references('id')->on('countries') ;        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
