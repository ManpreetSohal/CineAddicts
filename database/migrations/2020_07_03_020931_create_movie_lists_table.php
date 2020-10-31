<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('title');
            $table->String('username');
            $table->foreign('username')->references('username')->on('users')->onDelete('cascade');;
            $table->unsignedBigInteger('list_type_id');
            $table->foreign('list_type_id')->references('id')->on('list_types');
            $table->timestamps();
            $table->unique(['username', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_lists');
    }
}
